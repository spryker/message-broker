<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\EventDispatcher;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher|\Psr\EventDispatcher\EventDispatcherInterface|null
     */
    protected ?EventDispatcherInterface $eventDispatcher = null;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher|\Psr\EventDispatcher\EventDispatcherInterface|null $eventDispatcher
     */
    public function __construct(?EventDispatcherInterface $eventDispatcher, array $eventDispatcherSubscriberPlugins)
    {
        foreach ($eventDispatcherSubscriberPlugins as $eventDispatcherSubscriberPlugin) {
            $eventDispatcher->addSubscriber($eventDispatcherSubscriberPlugin);
        }

        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param object $event
     *
     * @return object|void
     */
    public function dispatch(object $event, string $eventName = null): object
    {
        return $this->eventDispatcher->dispatch($event);
    }
}
