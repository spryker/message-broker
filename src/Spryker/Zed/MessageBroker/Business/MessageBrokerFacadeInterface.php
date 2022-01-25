<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;

interface MessageBrokerFacadeInterface
{
    /**
     * Specification:
     * - Adds message attributes to the transfer object.
     * - Wraps message in a Symfony Envelope and sends it through the configured transport for this message.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function sendMessage(TransferInterface $messageTransfer): Envelope;

    /**
     * Specification:
     * - Starts a worker process for the defined channels.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer
     *
     * @return void
     */
    public function startWorker(MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer): void;

    /**
     * Specification:
     * - Prints debug information to the console.
     *
     * @api
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function printDebug(OutputInterface $output): void;
}
