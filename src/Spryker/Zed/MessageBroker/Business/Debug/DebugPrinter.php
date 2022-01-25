<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Debug;

use Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface;
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
     * @var \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface
     */
    protected ConfigFormatterInterface $configFormatter;

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
     * @var AsyncApiLoaderInterface
     */
    protected AsyncApiLoaderInterface $asyncApiLoader;

    /**
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $config
     * @param \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface $configFormatter
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface> $receiverPlugins
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface> $senderPlugins
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface> $messageHandlerPlugins
     * @param AsyncApiLoaderInterface $asyncApiLoader
     */
    public function __construct(
        MessageBrokerConfig $config,
        ConfigFormatterInterface $configFormatter,
        array $receiverPlugins,
        array $senderPlugins,
        array $messageHandlerPlugins,
        AsyncApiLoaderInterface $asyncApiLoader
    ) {
        $this->config = $config;
        $this->configFormatter = $configFormatter;
        $this->receiverPlugins = $receiverPlugins;
        $this->senderPlugins = $senderPlugins;
        $this->messageHandlerPlugins = $messageHandlerPlugins;
        $this->asyncApiLoader = $asyncApiLoader;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string|null $pathToAsyncApiFile
     *
     * @return void
     */
    public function printDebug(OutputInterface $output, ?string $pathToAsyncApiFile = null): void
    {
        if ($pathToAsyncApiFile === null) {
            $this->printDebugForConfiguration($output);
        }

        $this->printDebugForAsyncApi();
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printDebugForConfiguration(OutputInterface $output): void
    {
        $messageToChannelMap = $this->getMessageToChannelMap();
        $channelToTransportMap = $this->getChannelToTransportMap();
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
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $pathToAsyncApiFile
     *
     * @return void
     */
    protected function printDebugForAsyncApi(OutputInterface $output, string $pathToAsyncApiFile): void
    {
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMessageToChannelMap(): array
    {
        $messageToChannelMap = $this->config->getMessageToChannelMap();

        if (is_string($messageToChannelMap)) {
            $messageToChannelMap = $this->configFormatter->format($messageToChannelMap);
        }

        return $messageToChannelMap;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getChannelToTransportMap(): array
    {
        $channelToTransportMap = $this->config->getChannelToTransportMap();

        if (is_string($channelToTransportMap)) {
            $channelToTransportMap = $this->configFormatter->format($channelToTransportMap);
        }

        return $channelToTransportMap;
    }

    /**
     * @return array<string, array<string>>
     */
    protected function getMessagesToHandlerMap(): array
    {
        $messagesToHandlerMap = [];

        foreach ($this->messageHandlerPlugins as $messageHandlerPlugin) {
            $messagesToHandlerMap[get_class($messageHandlerPlugin)] = array_keys(iterator_to_array($messageHandlerPlugin->handles()));
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
