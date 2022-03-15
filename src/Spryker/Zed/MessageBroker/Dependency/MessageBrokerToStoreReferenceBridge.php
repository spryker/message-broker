<?php

namespace Spryker\Zed\MessageBroker\Dependency;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface;

class MessageBrokerToStoreReferenceBridge implements MessageBrokerToStoreReferenceFacadeInterface
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