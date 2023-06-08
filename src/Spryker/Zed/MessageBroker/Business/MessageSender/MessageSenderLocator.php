<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageSender;

use Spryker\Zed\MessageBroker\Business\ClientAttributeProvider\ClientAttributeProviderInterface;
use Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;

class MessageSenderLocator implements SendersLocatorInterface
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
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    protected array $messageSenderPlugins = [];

    /**
     * @var \Spryker\Zed\MessageBroker\Business\ClientAttributeProvider\ClientAttributeProviderInterface
     */
    protected $clientAttributeProvider;

    /**
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $config
     * @param \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface $configFormatter
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface> $messageSenderPlugins
     * @param \Spryker\Zed\MessageBroker\Business\ClientAttributeProvider\ClientAttributeProviderInterface $clientAttributeProvider
     */
    public function __construct(
        MessageBrokerConfig $config,
        ConfigFormatterInterface $configFormatter,
        array $messageSenderPlugins,
        ClientAttributeProviderInterface $clientAttributeProvider,
    ) {
        $this->config = $config;
        $this->configFormatter = $configFormatter;
        $this->messageSenderPlugins = $messageSenderPlugins;
        $this->clientAttributeProvider = $clientAttributeProvider;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return array<string, \Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    public function getSenders(Envelope $envelope): iterable
    {
        return $this->getMessageSenderPlugins($envelope);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return array<string, \Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    protected function getMessageSenderPlugins(Envelope $envelope): iterable
    {
        $clientNames = $this->getSenderClientNameForMessage($envelope);

        $clientMessageSenderPlugins = [];
        foreach ($this->messageSenderPlugins as $messageSenderPlugin) {
            foreach ($clientNames as $clientName) {
                if ($clientName === null || $clientName === $messageSenderPlugin->getTransportName()) {
                    $clientMessageSenderPlugins[$messageSenderPlugin->getTransportName()] = $messageSenderPlugin;
                }
            }
        }

        return $clientMessageSenderPlugins;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return array|null
     */
    protected function getSenderClientNameForMessage(Envelope $envelope): ?array
    {
        $channel =  $this->clientAttributeProvider->getChannelForMessageClass($envelope);

        $channelToSenderClientMap = $this->config->getChannelToTransportMap();

        if (is_string($channelToSenderClientMap)) {
            $channelToSenderClientMap = $this->configFormatter->format($channelToSenderClientMap);
        }

        $channelToSenderTransportMap = $this->config->getChannelToSenderTransportMap();

        if (is_string($channelToSenderTransportMap)) {
            $channelToSenderTransportMap = $this->configFormatter->format($channelToSenderTransportMap);
        }

        $channelToSenderClientMap = array_merge($channelToSenderClientMap, $channelToSenderTransportMap);

        if (isset($channelToSenderClientMap[$channel])) {
            if (is_array($channelToSenderClientMap[$channel])) {
                return $channelToSenderClientMap[$channel];
            }

            return [$channelToSenderClientMap[$channel]];
        }

        return null;
    }
}
