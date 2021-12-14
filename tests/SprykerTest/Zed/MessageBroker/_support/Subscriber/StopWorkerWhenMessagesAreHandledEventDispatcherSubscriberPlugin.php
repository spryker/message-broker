<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\_support\Subscriber;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\EventDispatcherSubscriberPluginInterface;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;
use Symfony\Component\Messenger\Event\WorkerStartedEvent;
use Symfony\Component\Messenger\Worker;

class StopWorkerWhenMessagesAreHandledEventDispatcherSubscriberPlugin extends AbstractPlugin implements EventDispatcherSubscriberPluginInterface
{
    protected ?Worker $worker = null;

    /**
     * @return void
     */
    public function onWorkerStarted(WorkerStartedEvent $event)
    {
        $this->worker = $event->getWorker();
    }

    /**
     * @return void
     */
    public function onWorkerMessageHandled(WorkerMessageHandledEvent $event)
    {
        $this->worker->stop();
    }

    /**
     * @return array<string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            WorkerStartedEvent::class => 'onWorkerStarted',
            WorkerMessageHandledEvent::class => 'onWorkerMessageHandled',
        ];
    }
}
