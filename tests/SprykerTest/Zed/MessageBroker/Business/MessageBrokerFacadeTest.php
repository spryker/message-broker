<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CorrelationIdTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException;
use Spryker\Zed\MessageBroker\Business\Stamp\CorrelationIdStamp;
use Spryker\Zed\MessageBroker\Communication\Plugin\CorrelationIdMessageDecoratorPlugin;
use SprykerTest\Zed\MessageBroker\Plugin\SomethingHappenedMessageHandlerPlugin;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Business
 * @group Facade
 * @group MessageBrokerFacadeTest
 * Add your own group annotations below this line
 */
class MessageBrokerFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const CHANNEL_NAME = 'channel';

    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester
     */
    protected $tester;

    /**
     * @var string|null
     */
    protected ?string $correlationId = null;

    /**
     * @return void
     */
    public function testPushMessageWithoutConfiguredHandlerThrowsAnException(): void
    {
        // Arrange
        $this->tester->setMessageToSenderChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        // Expect
        $this->expectException(NoHandlerForMessageException::class);

        // Act
        $this->tester->getFacade()->pushMessage($messageBrokerTestMessageTransfer);
    }

    /**
     * @return void
     */
    public function testPushMessageWithoutConfiguredMessageToSenderChannelMapThrowsAnException(): void
    {
        // Arrange
        $this->tester->setMessageHandlerPlugins([new SomethingHappenedMessageHandlerPlugin()]);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        // Expect
        $this->expectException(CouldNotMapMessageToChannelNameException::class);

        // Act
        $this->tester->getFacade()->pushMessage($messageBrokerTestMessageTransfer);
    }

    /**
     * @return void
     */
    public function testPushMessageAddsMetaDataToMessage(): void
    {
        // Arrange
        $this->tester->setMessageToSenderChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);

        $this->tester->setMessageSenderPlugins([$this->tester->getInMemoryMessageTransportPlugin()]);
        $this->tester->setMessageReceiverPlugins([$this->tester->getInMemoryMessageTransportPlugin()]);

        $this->tester->setMessageHandlerPlugins([new SomethingHappenedMessageHandlerPlugin()]);
        $this->tester->setMessageDecoratorPlugins([
            new CorrelationIdMessageDecoratorPlugin(),
        ]);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        // Act
        $envelope = $this->tester->getFacade()->pushMessage($messageBrokerTestMessageTransfer);

        $this->tester->assertMessageHasStamp($envelope, CorrelationIdStamp::class);

        /** @var CorrelationIdStamp $correlationIdStamp */
        $correlationIdStamp = $envelope->last(CorrelationIdStamp::class);
        $this->assertIsString($correlationIdStamp->getCorrelationId());
    }

    /**
     * @return void
     */
    public function testPushMessageSendsMessageWithSpecifiedClient(): void
    {
        // Arrange
        $this->tester->setSenderChannelToClientNameMap(static::CHANNEL_NAME, 'in-memory');
        $this->tester->setMessageToSenderChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);

        $this->tester->setMessageSenderPlugins([
            $this->tester->createSnsSenderPlugin(), // First sender should not be used.
            $this->tester->getInMemoryMessageTransportPlugin(),
        ]);

        $this->tester->setMessageHandlerPlugins([new SomethingHappenedMessageHandlerPlugin()]);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        // Act
        $envelope = $this->tester->getFacade()->pushMessage($messageBrokerTestMessageTransfer);

        $this->tester->assertMessageWasSentWithSender($envelope, 'in-memory');
    }

    /**
     * This method tests that when the message is associated with a channel but the channel is not bound
     * to a specific sender client.
     *
     * This case will ensure that messages can be sent with many senders.
     *
     * @return void
     */
    public function testPushMessageSendsMessageWithAllSendersWhenMessageChannelIsNotAssociatedWithASpecificSenderClient(): void
    {
        // Arrange
        $this->tester->setMessageToSenderChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);

        $this->tester->setMessageSenderPlugins([
            $this->tester->createSnsSenderPlugin(),
            $this->tester->getInMemoryMessageTransportPlugin(),
        ]);

        $this->tester->setMessageHandlerPlugins([new SomethingHappenedMessageHandlerPlugin()]);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        // Act
        $envelope = $this->tester->getFacade()->pushMessage($messageBrokerTestMessageTransfer);

        $this->tester->assertMessageWasSentWithSenders($envelope, ['sns', 'in-memory']);
    }

    /**
     * This method tests that when the message is associated with a channel but the channel is not bound
     * to a specific sender client.
     *
     * This case will ensure that messages can be sent with many senders.
     *
     * @return void
     */
    public function testPushedMessageCanBeConsumedByWorker(): void
    {
        // Arrange
        $this->tester->setMessageToSenderChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);

        $inMemoryMessageTransportMock = $this->tester->getInMemoryMessageTransportPlugin();
        $this->tester->setMessageSenderPlugins([$inMemoryMessageTransportMock]);
        $this->tester->setMessageReceiverPlugins([$inMemoryMessageTransportMock]);

        $this->tester->setMessageHandlerPlugins([new SomethingHappenedMessageHandlerPlugin()]);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        // Act
        $envelope = $this->tester->getFacade()->pushMessage($messageBrokerTestMessageTransfer);
        $this->tester->consumeMessages();

        // Assert
        $this->tester->assertMessageHasStamp($envelope, HandledStamp::class);
        $acknowledged = $inMemoryMessageTransportMock->getTransport()->getAcknowledged();
        $this->assertCount(1, $acknowledged, sprintf('Expected that exactly one Message was acknowledged but "%s" were acknowledged', count($acknowledged)));
    }
}
