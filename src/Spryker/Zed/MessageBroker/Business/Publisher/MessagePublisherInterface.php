<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Publisher;

use Generated\Shared\Transfer\MessageResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface MessagePublisherInterface
{
    public function sendMessage(TransferInterface $messageTransfer): MessageResponseTransfer;
}
