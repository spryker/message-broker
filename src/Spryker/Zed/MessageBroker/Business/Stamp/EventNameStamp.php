<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

class EventNameStamp implements StampInterface
{
    /**
     * @var string
     */
    protected string $eventName;

    /**
     * @param string $eventName
     */
    public function __construct(string $eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }
}
