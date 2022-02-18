<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\StoreReferenceBuilder;

interface StoreReferenceBuilderInterface
{
    /**
     * @return string
     */
    public function buildStoreReference(): string;
}
