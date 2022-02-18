<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\StoreReferenceBuilder;

use Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreFacadeInterface;

class StoreReferenceBuilder implements StoreReferenceBuilderInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreFacadeInterface $storeFacade
     */
    public function __construct(MessageBrokerToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return string
     */
    public function buildStoreReference(): string
    {
        return $this->storeFacade->getCurrentStore()->getName() . '_' . getenv('TENANT_IDENTIFIER');
    }
}
