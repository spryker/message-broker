<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Middleware;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
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
     * @var \Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface
     */
    protected $sendersLocator;

    public function __construct(SendersLocatorInterface $sendersLocator)
    {
        $this->sendersLocator = $sendersLocator;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param \Symfony\Component\Messenger\Middleware\StackInterface $stack
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope->with(new ChannelNameStamp('payment-events'));

        return $stack->next()->handle($envelope, $stack);
    }
}
