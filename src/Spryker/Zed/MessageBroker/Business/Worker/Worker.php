<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Worker;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Worker as SymfonyWorker;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Worker extends SymfonyWorker
{
    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageReceiverPluginInterface> $messageReceiverPlugins
     */
    public function __construct(
        array $messageReceiverPlugins,
        MessageBusInterface $bus,
        ?EventDispatcherInterface $eventDispatcher = null,
        ?LoggerInterface $logger = null
    ) {
        $receivers = [];

        foreach ($messageReceiverPlugins as $messageReceiverPlugin) {
            $receivers[$messageReceiverPlugin->getClientName()] = $messageReceiverPlugin;
        }

        parent::__construct($receivers, $bus, $eventDispatcher, $logger);
    }
}
