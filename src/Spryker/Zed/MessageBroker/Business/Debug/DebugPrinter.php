<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Debug;

use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class DebugPrinter implements DebugPrinterInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\MessageBrokerConfig
     */
    protected MessageBrokerConfig $config;

    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface>
     */
    protected array $receiverPlugins;

    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    protected array $senderPlugins;

    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface>
     */
    protected array $messageHandlerPlugins;

    /**
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $config
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface> $receiverPlugins
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface> $senderPlugins
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface> $messageHandlerPlugins
     */
    public function __construct(MessageBrokerConfig $config, array $receiverPlugins, array $senderPlugins, array $messageHandlerPlugins)
    {
        $this->config = $config;
        $this->receiverPlugins = $receiverPlugins;
        $this->senderPlugins = $senderPlugins;
        $this->messageHandlerPlugins = $messageHandlerPlugins;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function printDebug(OutputInterface $output): void
    {
        $messageToChannelMap = $this->config->getMessageToChannelMap();
        $channelToTransportMap = $this->config->getChannelToTransportMap();
        $messagesToHandlerMap = $this->getMessagesToHandlerMap();

        foreach ($messageToChannelMap as $messageClassName => $channelName) {
            $handlersForMessage = $this->getHandlersForMessage($messageClassName, $messagesToHandlerMap);
            $handlersForMessage = count($handlersForMessage) > 0 ? implode(PHP_EOL, $handlersForMessage) : 'No handler found';

            $table = new Table($output);
            $table->setHeaders(['Channel', 'Message', 'Transport', 'Handler']);
            $table->addRow([
                $channelName,
                $messageClassName,
                $channelToTransportMap[$channelName] ?? 'Not configured',
                $handlersForMessage,
            ]);

            $table->render();
        }
    }

    /**
     * @return array<string, array<string>>
     */
    protected function getMessagesToHandlerMap(): array
    {
        $messagesToHandlerMap = [];

        foreach ($this->messageHandlerPlugins as $messageHandlerPlugin) {
            $messagesToHandlerMap[get_class($messageHandlerPlugin)] = array_keys($messageHandlerPlugin->handles());
        }

        return $messagesToHandlerMap;
    }

    /**
     * @param string $messageClassName
     * @param array<string, array<string>> $messagesToHandlerMap
     *
     * @return array<string>
     */
    protected function getHandlersForMessage(string $messageClassName, array $messagesToHandlerMap): array
    {
        $handlersForMessage = [];

        foreach ($messagesToHandlerMap as $handlerClassName => $handledMessages) {
            if (in_array($messageClassName, $handledMessages)) {
                $handlersForMessage[] = $handlerClassName;
            }
        }

        return $handlersForMessage;
    }
}
