<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin;

use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word\CamelCaseToDash;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBroker\Business\Stamp\CorrelationIdStamp;
use Spryker\Zed\MessageBroker\Business\Stamp\EventNameStamp;
use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageDecoratorPluginInterface;
use Symfony\Component\Messenger\Envelope;

/**
 * @method \Spryker\Zed\MessageBroker\MessageBrokerConfig getConfig()
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface getFacade()
 */
class EventNameMessageDecoratorPlugin extends AbstractPlugin implements MessageDecoratorPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function decorateMessage(Envelope $envelope): Envelope
    {
        $eventName = get_class($envelope->getMessage());

        $eventName = str_replace(['Generated\Shared\Transfer\\', 'Transfer'], '', $eventName);
        $filter = new FilterChain();
        $filter
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $envelope->with(new EventNameStamp($filter->filter($eventName)));
    }
}
