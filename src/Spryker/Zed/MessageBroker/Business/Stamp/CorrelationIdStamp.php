<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Stamp;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Stamp\StampInterface;

class CorrelationIdStamp implements StampInterface
{
    /**
     * @var string|null
     */
    protected ?string $correlationId = null;

    public function __construct()
    {
        $this->correlationId = Uuid::uuid4()->toString();
    }

    /**
     * The `correlationId` will be added to all messages. It MUST be unique for one request to be able to debug messages for a given request.
     * All messages sent during this request will have the same `correlationId`.
     *
     * @api
     *
     * @return string
     */
    public function getCorrelationId(): string
    {
        return $this->correlationId;
    }
}
