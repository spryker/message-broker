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
     * @param \Symfony\Component\Messenger\Envelope $message
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function decorateMessage(Envelope $message): Envelope;
}
