<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\PublisherTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageAttributeProviderPluginInterface;

/**
 * @method \Spryker\Zed\MessageBroker\MessageBrokerConfig getConfig()
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface getFacade()
 */
class StoreReferenceMessageAttributeProviderPlugin extends AbstractPlugin implements MessageAttributeProviderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function provideMessageAttributes(MessageAttributesTransfer $messageAttributesTransfer): MessageAttributesTransfer
    {
        if (!getenv('STORE_NAME_REFERENCE_MAP')) {
            return $messageAttributesTransfer;
        }

        $storeReference = $this->getFacade()->getStoreReference();
        $messageAttributesTransfer->setStoreReference($storeReference);

        $publisher = $messageAttributesTransfer->getPublisher() ?? new PublisherTransfer();
        $messageAttributesTransfer->setPublisher($publisher->setStoreReference($storeReference));

        return $messageAttributesTransfer;
    }
}
