<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Channel;

use Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Symfony\Component\Messenger\Envelope;

class ChannelNameResolver implements ChannelNameResolverInterface
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
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $config
     * @param \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface $configFormatter
     */
    public function __construct(MessageBrokerConfig $config, ConfigFormatterInterface $configFormatter)
    {
        $this->config = $config;
        $this->configFormatter = $configFormatter;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return string
     */
    public function getChannelNameForMessage(Envelope $envelope): string
    {
        $channelNameMap = $this->config->getMessageToChannelMap();

        if (is_string($channelNameMap)) {
            $channelNameMap = $this->configFormatter->format($channelNameMap);
        }

        $messageClassName = get_class($envelope->getMessage());

        return $channelNameMap[$messageClassName];
    }
}
