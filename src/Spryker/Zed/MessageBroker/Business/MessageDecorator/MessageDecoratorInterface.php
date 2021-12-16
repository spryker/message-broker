<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageDecorator;

use Symfony\Component\Messenger\Envelope;

interface MessageDecoratorInterface
{
    /**
     * @param Envelope $message
     *
     * @return Envelope
     */
    public function decorateMessage(Envelope $message): Envelope;
}
