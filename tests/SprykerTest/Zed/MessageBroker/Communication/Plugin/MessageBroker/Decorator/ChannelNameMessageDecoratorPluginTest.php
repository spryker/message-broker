<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\MessageBroker\Decorator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Spryker\Zed\MessageBroker\Business\Stamp\ChannelNameStamp;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\Decorator\ChannelNameMessageDecoratorPlugin;
use Symfony\Component\Messenger\Envelope;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group Decorator
 * @group ChannelNameMessageDecoratorPluginTest
 * Add your own group annotations below this line
 */
class ChannelNameMessageDecoratorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDecorateMessageAddsChannelNameStampWithChannelForTheGivenMessage(): void
    {
        // Arrange
        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $this->tester->setMessageToChannelNameMap(MessageBrokerTestMessageTransfer::class, 'channel');

        $envelope = Envelope::wrap($messageBrokerTestMessageTransfer);
        $channelNameMessageDecoratorPlugin = new ChannelNameMessageDecoratorPlugin();

        // Act
        $envelope = $channelNameMessageDecoratorPlugin->decorateMessage($envelope);

        // Assert
        $this->tester->assertMessageHasStamp($envelope, ChannelNameStamp::class);
    }
}
