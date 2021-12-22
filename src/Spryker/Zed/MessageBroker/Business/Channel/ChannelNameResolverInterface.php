<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Channel;

use Symfony\Component\Messenger\Envelope;

interface ChannelNameResolverInterface
{
    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return mixed
     */
    public function getChannelNameForMessage(Envelope $envelope);
}
