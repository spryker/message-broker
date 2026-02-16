<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageAttributeProviderPluginInterface;

/**
 * @method \Spryker\Zed\MessageBroker\MessageBrokerConfig getConfig()
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface getFacade()
 */
class AppIdentifierMessageAttributeProviderPlugin extends AbstractPlugin implements MessageAttributeProviderPluginInterface
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
        $aopAppIdentifier = getenv('AOP_APP_IDENTIFIER');
        if ($aopAppIdentifier === false) {
            return $messageAttributesTransfer;
        }

        $emitter = $messageAttributesTransfer->getEmitter() ?? $aopAppIdentifier;
        $messageAttributesTransfer->setEmitter($emitter);

        return $messageAttributesTransfer;
    }
}
