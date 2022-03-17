<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\StoreReferenceBuilder;

use Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreFacadeInterface;
use Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreReferenceFacadeInterface;

class StoreReferenceBuilder implements StoreReferenceBuilderInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreFacadeInterface
     */
    protected $storeReferenceFacade;

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
    public function buildStoreReference(): string
    {
        $storeName = $this->storeFacade->getCurrentStore()->getName();
        $store = $this->storeReferenceFacade->getStoreByStoreName($storeName);

        return $store->getStoreReference();
    }
}
