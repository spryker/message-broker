<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\StoreReferenceReceiver;

use Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreFacadeInterface;
use Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreReferenceFacadeInterface;

class StoreReferenceReceiver implements StoreReferenceReceiverInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreFacadeInterface
     */
    protected MessageBrokerToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreReferenceFacadeInterface
     */
    protected MessageBrokerToStoreReferenceFacadeInterface $storeReferenceFacade;

    /**
     * @param \Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreReferenceFacadeInterface $storeReferenceFacade
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
        $storeName = $this->storeFacade->getCurrentStore()->getName();
        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreName($storeName);

        return $storeTransfer->getStoreReference();
    }
}
