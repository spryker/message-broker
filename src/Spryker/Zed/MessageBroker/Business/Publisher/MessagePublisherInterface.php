<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Publisher;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Symfony\Component\Messenger\Envelope;

interface MessagePublisherInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function sendMessage(TransferInterface $messageTransfer): Envelope;
}
