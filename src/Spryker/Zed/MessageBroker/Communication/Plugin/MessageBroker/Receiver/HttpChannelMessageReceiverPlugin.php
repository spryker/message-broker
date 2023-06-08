<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\Receiver;

use Exception;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Receiver\QueueReceiverInterface;

/**
 * @method \Spryker\Zed\MessageBroker\MessageBrokerConfig getConfig()
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface getFacade()
 */
class HttpChannelMessageReceiverPlugin extends AbstractPlugin implements MessageReceiverPluginInterface, QueueReceiverInterface
{
    /**
     * @var array<string>
     *
     */
    protected $channels = [];

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
     * @return self
     */
    public function setChannels(array $channels): self
    {
        $this->channels = $channels;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string> $queueNames
     *
     * @return array<\Symfony\Component\Messenger\Envelope>
     */
    public function getFromQueues(array $queueNames): iterable
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     *
     * @api
     *
     * @return array<\Symfony\Component\Messenger\Envelope>
     */
    public function get(): iterable
    {
        return $this->getFacade()->getEnvelopes($this->channels);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function ack(Envelope $envelope): void
    {
        $this->getFacade()->deleteEnvelope($envelope);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function reject(Envelope $envelope): void
    {
        $this->getFacade()->deleteEnvelope($envelope);
    }
}
