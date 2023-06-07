<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Sender\Client\Locator;

use Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBroker\Business\Sender\Client\SenderClientInterface;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;

class SenderClientLocator implements SenderClientLocatorInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\MessageBrokerConfig
     */
    protected MessageBrokerConfig $config;

    /**
     * @var array<string, \Spryker\Zed\MessageBroker\Business\Sender\Client\SenderClientInterface>
     */
    protected array $senderClients = [];

    /**
     * @var \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface
     */
    protected ConfigFormatterInterface $configFormatter;

    /**
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $config
     * @param array<string, \Spryker\Zed\MessageBroker\Business\Sender\Client\SenderClientInterface> $senderClients
     * @param \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface $configFormatter
     */
    public function __construct(MessageBrokerConfig $config, array $senderClients, ConfigFormatterInterface $configFormatter)
    {
        $this->config = $config;
        $this->senderClients = $senderClients;
        $this->configFormatter = $configFormatter;
    }

    /**
     * @param string $channelName
     *
     * @return \Spryker\Zed\MessageBroker\Business\Sender\Client\SenderClientInterface
     */
    public function getSenderClientByChannelName(string $channelName): SenderClientInterface
    {
        $channelToSenderClientMap = $this->config->getChannelToSenderTransportMap();

        if (is_string($channelToSenderClientMap)) {
            $channelToSenderClientMap = $this->configFormatter->format($channelToSenderClientMap);
        }

        return $this->senderClients[$channelToSenderClientMap[$channelName]];
    }
}
