<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Symfony\Component\Messenger\Envelope;

interface MessageBrokerFacadeInterface
{
    /**
     * Specification:
     * - Push a message through a configured transport.
     * - Wraps the message within an envelope and add stamps (metadata) to it.
     *
     * @api
     *
     * @param object $message
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function pushMessage(object $message): Envelope;

    /**
     * Specification:
     * - Starts a worker process for the defined channels.
     *
     * @api
     *
     * @param MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer
     *
     * @return void
     */
    public function startWorker(MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer): void;
}
