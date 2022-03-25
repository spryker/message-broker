<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Dependency;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface;

class MessageBrokerToStoreReferenceFacadeBridge implements MessageBrokerToStoreReferenceFacadeInterface
{
    /**
     * @var \Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface
     */
    protected StoreReferenceFacadeInterface $storeReferenceFacade;

    /**
     * @param \Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface $storeReferenceFacade
     */
    public function __construct($storeReferenceFacade)
    {
        $this->storeReferenceFacade = $storeReferenceFacade;
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreName(string $storeName): StoreTransfer
    {
        return $this->storeReferenceFacade->getStoreByStoreName($storeName);
    }
}