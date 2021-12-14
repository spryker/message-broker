<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageHandler;

use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageHandlerPluginInterface;
use Symfony\Component\Messenger\Handler\HandlersLocator;

class MessageHandlerLocator extends HandlersLocator
{
    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageHandlerPluginInterface> $messageHandlerPlugins
     */
    public function __construct(array $messageHandlerPlugins)
    {
        $handlers = $this->prepareHandlers($messageHandlerPlugins);

        parent::__construct($handlers);
    }

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageHandlerPluginInterface> $messageHandlerPlugins
     *
     * @return array<string, callable>
     */
    protected function prepareHandlers(array $messageHandlerPlugins): array
    {
        $handlers = [];

        foreach ($messageHandlerPlugins as $messageHandlerPlugin) {
            $handlers = $this->addHandlersFromHandlerPlugin($messageHandlerPlugin, $handlers);
        }

        return $handlers;
    }

    /**
     * @param \Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageHandlerPluginInterface $messageHandlerPlugin
     * @param array<string, callable> $handlers
     *
     * @return array<string, callable>
     */
    protected function addHandlersFromHandlerPlugin(MessageHandlerPluginInterface $messageHandlerPlugin, array $handlers): array
    {
        foreach ($messageHandlerPlugin->handles() as $handleMessageClassName => $callable) {
            if (!isset($handlers[$handleMessageClassName])) {
                $handlers[$handleMessageClassName] = [];
            }

            $handlers[$handleMessageClassName][] = $callable;
        }

        return $handlers;
    }
}
