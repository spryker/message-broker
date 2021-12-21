<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Messenger\Envelope;

/**
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerBusinessFactory getFactory()
 */
class MessageBrokerFacade extends AbstractFacade implements MessageBrokerFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param object $message
     *
     * @return Envelope
     */
    public function pushMessage(object $message): Envelope
    {
        return $this->getFactory()->createMessagePublisher()->pushMessage($message);
    }

    /**
     * @param array $channels
     */
    public function startWorker(array $channels): void
    {
        $this->getFactory()->createWorker()->run(['queues' => $channels]);
    }
}
