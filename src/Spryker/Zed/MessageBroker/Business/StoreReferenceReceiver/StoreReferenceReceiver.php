<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\StoreReferenceReceiver;

use Spryker\Zed\MessageBroker\Dependency\Facade\MessageBrokerToStoreFacadeInterface;
use Spryker\Zed\MessageBroker\Dependency\Facade\MessageBrokerToStoreReferenceFacadeInterface;

class StoreReferenceReceiver implements StoreReferenceReceiverInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Dependency\Facade\MessageBrokerToStoreFacadeInterface
     */
    protected MessageBrokerToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\MessageBroker\Dependency\Facade\MessageBrokerToStoreReferenceFacadeInterface
     */
    protected MessageBrokerToStoreReferenceFacadeInterface $storeReferenceFacade;

    /**
     * @param \Spryker\Zed\MessageBroker\Dependency\Facade\MessageBrokerToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\MessageBroker\Dependency\Facade\MessageBrokerToStoreReferenceFacadeInterface $storeReferenceFacade
     */
    public function __construct(
        MessageBrokerToStoreFacadeInterface $storeFacade,
        MessageBrokerToStoreReferenceFacadeInterface $storeReferenceFacade
    ) {
        $this->storeFacade = $storeFacade;
        $this->storeReferenceFacade = $storeReferenceFacade;
    }

    /**
     * @return string
     */
    public function getStoreReference(): string
    {
        $storeName = (string)$this->storeFacade->getCurrentStore()->getName();
        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreName($storeName);

        return $storeTransfer->getStoreReferenceOrFail();
    }
}
