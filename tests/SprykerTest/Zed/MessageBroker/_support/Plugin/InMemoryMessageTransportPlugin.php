<?php

namespace SprykerTest\Zed\MessageBroker\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageReceiverPluginInterface;
use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageSenderPluginInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\TransportInterface;

class InMemoryMessageTransportPlugin extends AbstractPlugin implements MessageSenderPluginInterface, MessageReceiverPluginInterface, TransportInterface
{
    /**
     * @var TransportInterface|null
     */
    protected ?TransportInterface $transport = null;

    /**
     * @param TransportInterface|null $transport
     */
    public function __construct(?TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @return string
     */
    public function getChannelName(): string
    {
        return '*';
    }

    /**
     * @return iterable
     */
    public function get(): iterable
    {
        return $this->transport->get();
    }

    /**
     * @param Envelope $envelope
     */
    public function ack(Envelope $envelope): void
    {
        $this->transport->ack($envelope);
    }

    /**
     * @param Envelope $envelope
     */
    public function reject(Envelope $envelope): void
    {
        $this->transport->reject($envelope);
    }

    /**
     * @param Envelope $envelope
     * @return Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        return $this->transport->send($envelope);
    }
}
