<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Output\OutputInterface;
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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function sendMessage(TransferInterface $messageTransfer): Envelope
    {
        return $this->getFactory()->createMessagePublisher()->sendMessage($messageTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer
     *
     * @return void
     */
    public function startWorker(MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer): void
    {
        $this->getFactory()->createWorker()->runWorker($messageBrokerWorkerConfigTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function printDebug(OutputInterface $output): void
    {
        $this->getFactory()->createDebugPrinter()->printDebug($output);
    }
}
