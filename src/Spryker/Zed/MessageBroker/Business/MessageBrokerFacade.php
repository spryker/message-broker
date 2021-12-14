<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

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
     * @return void
     */
    public function pushMessage(object $message): void
    {
        $this->getFactory()->createMessagePublisher()->pushMessage($message);
    }
}
