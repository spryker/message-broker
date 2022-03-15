<?php

namespace Spryker\Zed\MessageBroker\Dependency;

use Generated\Shared\Transfer\StoreTransfer;

interface MessageBrokerToStoreReferenceFacadeInterface
{
    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreName(string $storeName): StoreTransfer;
}