<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Publisher;

use Symfony\Component\Messenger\Envelope;

interface MessagePublisherInterface
{
    /**
     * @param object $message
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function pushMessage(object $message): Envelope;
}
