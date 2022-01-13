<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Publisher;

use Spryker\Zed\MessageBroker\Business\MessageDecorator\MessageDecoratorInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class MessagePublisher implements MessagePublisherInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Business\MessageDecorator\MessageDecoratorInterface
     */
    protected MessageDecoratorInterface $messageDecorator;

    /**
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    protected MessageBusInterface $messageBus;

    /**
     * @param \Spryker\Zed\MessageBroker\Business\MessageDecorator\MessageDecoratorInterface $messageDecorator
     * @param \Symfony\Component\Messenger\MessageBusInterface $messageBus
     */
    public function __construct(MessageDecoratorInterface $messageDecorator, MessageBusInterface $messageBus)
    {
        $this->messageDecorator = $messageDecorator;
        $this->messageBus = $messageBus;
    }

    /**
     * @param object $message
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function pushMessage(object $message): Envelope
    {
        return $this->messageBus->dispatch(
            $this->decorateMessage($message),
        );
    }

    /**
     * @param object $message
     *
     * @return object
     */
    protected function decorateMessage(object $message): object
    {
        if (!($message instanceof Envelope)) {
            $message = Envelope::wrap($message);
        }

        return $this->messageDecorator->decorateMessage($message);
    }
}
