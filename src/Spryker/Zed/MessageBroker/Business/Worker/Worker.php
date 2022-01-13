<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Worker;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\EventListener\StopWorkerOnFailureLimitListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnMemoryLimitListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnMessageLimitListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnTimeLimitListener;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Worker as SymfonyWorker;

class Worker implements WorkerInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    protected ?LoggerInterface $logger;

    /**
     * @var \Symfony\Component\Messenger\Worker
     */
    protected SymfonyWorker $worker;

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageReceiverPluginInterface> $messageReceiverPlugins
     * @param \Symfony\Component\Messenger\MessageBusInterface $bus
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(
        array $messageReceiverPlugins,
        MessageBusInterface $bus,
        EventDispatcherInterface $eventDispatcher,
        ?LoggerInterface $logger = null
    ) {
        $receivers = [];

        foreach ($messageReceiverPlugins as $messageReceiverPlugin) {
            $receivers[$messageReceiverPlugin->getClientName()] = $messageReceiverPlugin;
        }

        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;

        $this->worker = new SymfonyWorker($receivers, $bus, $eventDispatcher, $logger);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer
     *
     * @return void
     */
    public function runWorker(MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer): void
    {
        if ($messageBrokerWorkerConfigTransfer->getLimit()) {
            $this->eventDispatcher->addSubscriber(new StopWorkerOnMessageLimitListener($messageBrokerWorkerConfigTransfer->getLimit(), $this->logger));
        }

        if ($messageBrokerWorkerConfigTransfer->getFailureLimit()) {
            $this->eventDispatcher->addSubscriber(new StopWorkerOnFailureLimitListener($messageBrokerWorkerConfigTransfer->getFailureLimit(), $this->logger));
        }

        if ($messageBrokerWorkerConfigTransfer->getMemoryLimit()) {
            $this->eventDispatcher->addSubscriber(new StopWorkerOnMemoryLimitListener($messageBrokerWorkerConfigTransfer->getMemoryLimit(), $this->logger));
        }

        if ($messageBrokerWorkerConfigTransfer->getTimeLimit()) {
            $this->eventDispatcher->addSubscriber(new StopWorkerOnTimeLimitListener($messageBrokerWorkerConfigTransfer->getTimeLimit(), $this->logger));
        }

        $options = [
            'queues' => $messageBrokerWorkerConfigTransfer->getQueues(),
        ];

        if ($messageBrokerWorkerConfigTransfer->getSleep()) {
            $options['sleep'] = $messageBrokerWorkerConfigTransfer->getSleep();
        }

        $this->run($options);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function run(array $options): void
    {
        $this->worker->run($options);
    }
}
