<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\Console;

use Codeception\Test\Unit;
use Spryker\Zed\MessageBroker\Communication\Plugin\Console\MessageBrokerWorkerConsole;
use SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester;
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
 * @group MessageBrokerWorkerConsoleTest
 * Add your own group annotations below this line
 */
class MessageBrokerWorkerConsoleTest extends Unit
{
    /**
     * @var array<string>
     */
    protected const QUEUE_NAMES = ['queueName1', 'queueName2'];

    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester
     */
    protected MessageBrokerCommunicationTester $tester;

    /**
     * @return void
     */
    public function testArgumentQueuesIsPassedToWorker(): void
    {
        $commandTester = $this->tester->getWorkerConsoleCommandTester();

        $arguments = [
            MessageBrokerWorkerConsole::ARGUMENT_QUEUES => static::QUEUE_NAMES,
        ];

        $commandTester->execute($arguments);

        $this->tester->assertReceivedOption('queues', static::QUEUE_NAMES);
        $this->tester->assertReceivedOption('sleep', 1000000);

        $this->tester->assertEventDispatcherDoesNotHasListenersForEvent(WorkerRunningEvent::class);
        $this->tester->assertEventDispatcherDoesNotHasListenersForEvent(WorkerMessageFailedEvent::class);

        $this->assertSame(MessageBrokerWorkerConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('Quit the worker with CONTROL-C.', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testWhenLimitOptionIsUsedStopWorkerOnMessageLimitListenerIsSubscribed(): void
    {
        $commandTester = $this->tester->getWorkerConsoleCommandTester();

        $arguments = [
            MessageBrokerWorkerConsole::ARGUMENT_QUEUES => static::QUEUE_NAMES,
            '--' . MessageBrokerWorkerConsole::OPTION_MESSAGE_LIMIT => 1,
        ];

        $commandTester->execute($arguments);

        $this->tester->assertEventDispatcherHasListenersForEvent(WorkerRunningEvent::class);

        $this->assertSame(MessageBrokerWorkerConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('processed 1 messages', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testWhenFailureLimitOptionIsUsedStopWorkerOnFailureLimitListenerIsSubscribed(): void
    {
        $commandTester = $this->tester->getWorkerConsoleCommandTester();

        $arguments = [
            MessageBrokerWorkerConsole::ARGUMENT_QUEUES => static::QUEUE_NAMES,
            '--' . MessageBrokerWorkerConsole::OPTION_FAILURE_LIMIT => 1,
        ];

        $commandTester->execute($arguments);

        $this->tester->assertEventDispatcherHasListenersForEvent(WorkerMessageFailedEvent::class);

        $this->assertSame(MessageBrokerWorkerConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('reached 1 failed messages', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testWhenMemoryLimitOptionIsUsedStopWorkerOnMemoryLimitListenerIsSubscribed(): void
    {
        $commandTester = $this->tester->getWorkerConsoleCommandTester();

        $arguments = [
            MessageBrokerWorkerConsole::ARGUMENT_QUEUES => static::QUEUE_NAMES,
            '--' . MessageBrokerWorkerConsole::OPTION_MEMORY_LIMIT => 1,
        ];

        $commandTester->execute($arguments);

        $this->tester->assertEventDispatcherHasListenersForEvent(WorkerRunningEvent::class);

        $this->assertSame(MessageBrokerWorkerConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('exceeded 1 of memory', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testWhenTimeLimitOptionIsUsedStopWorkerOnTimeLimitListenerIsSubscribed(): void
    {
        $commandTester = $this->tester->getWorkerConsoleCommandTester();

        $arguments = [
            MessageBrokerWorkerConsole::ARGUMENT_QUEUES => static::QUEUE_NAMES,
            '--' . MessageBrokerWorkerConsole::OPTION_TIME_LIMIT => 1,
        ];

        $commandTester->execute($arguments);

        $this->tester->assertEventDispatcherHasListenersForEvent(WorkerRunningEvent::class);
        $this->tester->assertEventDispatcherHasListenersForEvent(WorkerStartedEvent::class);

        $this->assertSame(MessageBrokerWorkerConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('been running for 1s', $commandTester->getDisplay());
    }
}
