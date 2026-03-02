<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Logger;

use Symfony\Component\Messenger\Envelope;

interface MessageLoggerInterface
{
    public function logInfo(Envelope $envelope, float $startMicrotime): void;

    public function logError(Envelope $envelope, float $startMicrotime, string $errorMessage): void;
}
