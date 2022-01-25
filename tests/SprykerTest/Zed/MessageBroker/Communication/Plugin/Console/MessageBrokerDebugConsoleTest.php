<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\Console;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Spryker\Zed\MessageBroker\Communication\Plugin\Console\MessageBrokerDebugConsole;
use Spryker\Zed\MessageBroker\Communication\Plugin\Console\MessageBrokerWorkerConsole;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester;
use SprykerTest\Zed\MessageBroker\Plugin\SomethingHappenedMessageHandlerPlugin;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;
use Symfony\Component\Messenger\Event\WorkerStartedEvent;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Communication
 * @group Plugin
 * @group Console
 * @group MessageBrokerDebugConsoleTest
 * Add your own group annotations below this line
 */
class MessageBrokerDebugConsoleTest extends Unit
{
    public const CHANNEL_NAME = 'test-channel';
    public const SQS_TRANSPORT_NAME = 'sqs';
    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester
     */
    protected MessageBrokerCommunicationTester $tester;

    /**
     * @return void
     */
    public function testPrintsDebugInformationOfConfiguredChannelMessageAndTransport(): void
    {
        // Arrange
        $this->tester->setMessageToChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToTransportMap(static::CHANNEL_NAME, static::SQS_TRANSPORT_NAME);

        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([]);

        // Assert
        $this->assertSame(MessageBrokerDebugConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('test-channel', $commandTester->getDisplay());
        $this->assertStringContainsString('Generated\Shared\Transfer\MessageBrokerTestMessageTransfer', $commandTester->getDisplay());
        $this->assertStringContainsString('sqs', $commandTester->getDisplay());
        $this->assertStringContainsString('No handler found', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testPrintsDebugInformationOfConfiguredChannelMessageTransportAndHandlerIfConfigured(): void
    {
        // Arrange
        $this->tester->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_HANDLER, [new SomethingHappenedMessageHandlerPlugin()]);
        $this->tester->setMessageToChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToTransportMap(static::CHANNEL_NAME, static::SQS_TRANSPORT_NAME);

        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([]);

        //Assert
        $this->assertSame(MessageBrokerDebugConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('test-channel', $commandTester->getDisplay());
        $this->assertStringContainsString('Generated\Shared\Transfer\MessageBrokerTestMessageTransfer', $commandTester->getDisplay());
        $this->assertStringContainsString('sqs', $commandTester->getDisplay());
        $this->assertStringContainsString('SprykerTest\Zed\MessageBroker\Plugin\SomethingHappenedMessageHandlerPlugin', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testPrintsDebugInformationAgainstAnAsyncApiFile(): void
    {
        // Arrange
//        $this->tester->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_HANDLER, [new SomethingHappenedMessageHandlerPlugin()]);
//        $this->tester->setMessageToChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
//        $this->tester->setChannelToTransportMap(static::CHANNEL_NAME, static::SQS_TRANSPORT_NAME);

        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([]);

        //Assert
        $this->assertSame(MessageBrokerDebugConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('test-channel', $commandTester->getDisplay());
        $this->assertStringContainsString('Generated\Shared\Transfer\MessageBrokerTestMessageTransfer', $commandTester->getDisplay());
        $this->assertStringContainsString('sqs', $commandTester->getDisplay());
        $this->assertStringContainsString('SprykerTest\Zed\MessageBroker\Plugin\SomethingHappenedMessageHandlerPlugin', $commandTester->getDisplay());
    }
}
