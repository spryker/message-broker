<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Publisher;

use Spryker\Zed\MessageBroker\Business\MessageDecorator\MessageDecoratorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessagePublisher implements MessagePublisherInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Business\MessageDecorator\MessageDecoratorInterface
     */
    protected ?MessageDecoratorInterface $messageDecorator = null;

    /**
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    protected ?MessageBusInterface $messageBus = null;

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
     * @return void
     */
    public function pushMessage(object $message): void
    {
        $this->messageBus->dispatch(
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
        return $this->messageDecorator->decorateMessage($message);
    }
}
