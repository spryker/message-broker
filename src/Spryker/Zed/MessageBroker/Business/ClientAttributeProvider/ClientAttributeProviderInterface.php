<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\ClientAttributeProvider;

use Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;

interface ClientAttributeProviderInterface
{
    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException
     *
     * @return string
     */
    public function getChannelForMessageClass(Envelope $envelope): string;
}
