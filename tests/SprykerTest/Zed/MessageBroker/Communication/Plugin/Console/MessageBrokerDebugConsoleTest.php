<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\Console;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Spryker\Zed\MessageBroker\Communication\Plugin\Console\MessageBrokerWorkerConsole;
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
    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester
     */
    protected MessageBrokerCommunicationTester $tester;

    /**
     * @return void
     */
    public function testPrintsDebugInformation(): void
    {
        $commandTester = $this->tester->getWorkerConsoleCommandTester();

        $commandTester->execute([]);

        $this->tester->assertReceivedOption('queues', static::CHANNEL_NAMES);
        $this->tester->assertReceivedOption('sleep', 1000000);

        $this->tester->assertEventDispatcherDoesNotHasListenersForEvent(WorkerRunningEvent::class);
        $this->tester->assertEventDispatcherDoesNotHasListenersForEvent(WorkerMessageFailedEvent::class);

        $this->assertSame(MessageBrokerWorkerConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('Quit the worker with CONTROL-C.', $commandTester->getDisplay());
    }
}
