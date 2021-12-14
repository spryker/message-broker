<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Business;

use Codeception\Test\Unit;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageDecorator\CorrelationIdMessageDecoratorPlugin;
use SprykerTest\Zed\MessageBroker\Messages\SomethingHappenedEvent;
use SprykerTest\Zed\MessageBroker\Plugin\SomethingHappenedMessageHandlerPlugin;

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
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPushMessageAddsMetaDataToMessage(): void
    {
        // Arrange
        // SNS SQ usage example
//        $this->tester->setMessageSenderPlugins([$this->tester->createSnsSenderPlugin()]);
//        $this->tester->setMessageReceiverPlugins([$this->tester->createAwsSqsReceiverPlugin()]);

        // In memory transport for testing
        $this->tester->setMessageSenderPlugins([$this->tester->getInMemoryMessageTransportPlugin()]);
        $this->tester->setMessageReceiverPlugins([$this->tester->getInMemoryMessageTransportPlugin()]);

        $this->tester->setMessageHandlerPlugins([new SomethingHappenedMessageHandlerPlugin()]);
        $this->tester->setMessageDecoratorPlugins([new CorrelationIdMessageDecoratorPlugin()]);

        $somethingHappenedEvent = new SomethingHappenedEvent(['foo' => 'bar']);

        // Act
        $this->tester->getFacade()->pushMessage($somethingHappenedEvent);

        // Assert
        $this->tester->assertMessageHasHeader(SomethingHappenedEvent::class, CorrelationIdMessageDecoratorPlugin::class, function (CorrelationIdMessageDecoratorPlugin $correlationIdMessageDecoratorPlugin) {
            $this->assertNotNull($correlationIdMessageDecoratorPlugin->getCorrelationId());
        });

        $this->tester->consumeMessages();
    }
}
