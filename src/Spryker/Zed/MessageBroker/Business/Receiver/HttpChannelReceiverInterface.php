<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Receiver;

use Symfony\Component\Messenger\Envelope;

interface HttpChannelReceiverInterface
{
    /**
     * @param array<string> $channels
     *
     * @return array<\Symfony\Component\Messenger\Envelope>
     */
    public function receive(array $channels): iterable;

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param array $channels
     *
     * @return void
     */
    public function delete(Envelope $envelope, array $channels): void;
}
