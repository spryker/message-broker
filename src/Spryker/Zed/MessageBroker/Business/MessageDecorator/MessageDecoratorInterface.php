<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageDecorator;

interface MessageDecoratorInterface
{
    /**
     * @param object $message
     *
     * @return object
     */
    public function decorateMessage(object $message): object;
}
