<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageReceiverPluginInterface;
use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageSenderPluginInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Symfony\Component\Messenger\Transport\TransportInterface;

class InMemoryMessageTransportPlugin extends AbstractPlugin implements MessageSenderPluginInterface, MessageReceiverPluginInterface, TransportInterface
{
    /**
     * @var \Symfony\Component\Messenger\Transport\InMemoryTransport
     */
    protected InMemoryTransport $transport;

    /**
     * @param \Symfony\Component\Messenger\Transport\InMemoryTransport $transport
     */
    public function __construct(InMemoryTransport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @return string
     */
    public function getClientName(): string
    {
        return 'in-memory';
    }

    /**
     * @return iterable
     */
    public function get(): iterable
    {
        return $this->transport->get();
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function ack(Envelope $envelope): void
    {
        $this->transport->ack($envelope);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function reject(Envelope $envelope): void
    {
        $this->transport->reject($envelope);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        return $this->transport->send($envelope);
    }

    /**
     * @return \Symfony\Component\Messenger\Transport\InMemoryTransport
     */
    public function getTransport(): InMemoryTransport
    {
        return $this->transport;
    }
}