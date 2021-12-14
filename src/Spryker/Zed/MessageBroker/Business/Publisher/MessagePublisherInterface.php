<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Publisher;

interface MessagePublisherInterface
{
    /**
     * @param object $message
     *
     * @return void
     */
    public function pushMessage(object $message): void;
}
