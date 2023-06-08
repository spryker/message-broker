<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\Sender;

use Exception;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Receiver\QueueReceiverInterface;

/**
 * @method \Spryker\Zed\MessageBroker\MessageBrokerConfig getConfig()
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface getFacade()
 */
class HttpChannelMessageSenderPlugin extends AbstractPlugin implements MessageSenderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getTransportName(): string
    {
        return MessageBrokerConfig::HTTP_CHANNEL_TRANSPORT;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Symfony\Component\Messenger\Exception\TransportException
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        return $this->getFacade()->sendEnvelopeWithHttpChannel($envelope);
    }
}
