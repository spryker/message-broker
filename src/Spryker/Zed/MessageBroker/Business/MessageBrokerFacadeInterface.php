<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business;

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
     * @return Envelope
     */
    public function pushMessage(object $message): Envelope;

    /**
     * @param array $channels
     */
    public function startWorker(array $channels): void;
}
