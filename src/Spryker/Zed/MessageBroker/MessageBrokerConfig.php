<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker;

use Spryker\Shared\MessageBroker\MessageBrokerConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MessageBrokerConfig extends AbstractBundleConfig
{
    /**
     * This configuration can to be done via environment variable.
     *
     * @api
     *
     * @return array<string, string>|string
     */
    public function getMessageToChannelMap()
    {
        if (getenv('AOP_MESSAGE_TO_CHANNEL_MAP') !== false) {
            return getenv('AOP_MESSAGE_TO_CHANNEL_MAP');
        }

        if ($this->getConfig()->hasKey(MessageBrokerConstants::MESSAGE_TO_CHANNEL_MAP)) {
            return $this->get(MessageBrokerConstants::MESSAGE_TO_CHANNEL_MAP);
        }

        return [];
    }

    /**
     * This configuration can to be done via environment variable.
     *
     * @api
     *
     * @return array<string, string>|string
     */
    public function getSenderChannelToClientMap()
    {
        if (getenv('AOP_SENDER_CHANNEL_TO_CLIENT_MAP') !== false) {
            return getenv('AOP_SENDER_CHANNEL_TO_CLIENT_MAP');
        }

        if ($this->getConfig()->hasKey(MessageBrokerConstants::SENDER_CHANNEL_TO_CLIENT_MAP)) {
            return $this->get(MessageBrokerConstants::SENDER_CHANNEL_TO_CLIENT_MAP);
        }

        return [];
    }
}
