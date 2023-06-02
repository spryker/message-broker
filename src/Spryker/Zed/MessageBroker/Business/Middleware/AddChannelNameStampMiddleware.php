<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Middleware;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Spryker\Zed\MessageBroker\Business\ClientAttributeProvider\ClientAttributeProviderInterface;
use Spryker\Zed\MessageBroker\Business\Receiver\Stamp\ChannelNameStamp;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Event\SendMessageToTransportsEvent;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AddChannelNameStampMiddleware implements MiddlewareInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Business\ClientAttributeProvider\ClientAttributeProviderInterface
     */
    protected $clientAttributeProvider;

    /**
     * @param \Spryker\Zed\MessageBroker\Business\ClientAttributeProvider\ClientAttributeProviderInterface $clientAttributeProvider
     */
    public function __construct(ClientAttributeProviderInterface $clientAttributeProvider)
    {
        $this->clientAttributeProvider = $clientAttributeProvider;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param \Symfony\Component\Messenger\Middleware\StackInterface $stack
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $channel = $this->clientAttributeProvider->getChannelForMessageClass($envelope);
        $envelope->with(new ChannelNameStamp($channel));

        return $stack->next()->handle($envelope, $stack);
    }
}
