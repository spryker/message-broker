<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageValidator;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreFacadeInterface;

class StoreReferenceMessageValidator implements MessageValidatorInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const VALIDATION_ERROR_STORE_REFERENCE_ERROR = 'Invalid storeReference in message for current queue.';

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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $message
     *
     * @return bool
     */
    public function isValidMessage(TransferInterface $message): bool
    {
        $storeReference = $this->getStoreReference();
        if ($storeReference == $message->getMessageAttributes()->getStoreReference()) {
            $this->getLogger()->error(static::VALIDATION_ERROR_STORE_REFERENCE_ERROR, [
                'message' => $message->toArray(),
                'storeReference' => $storeReference,
            ]);

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    protected function getStoreReference(): string
    {
        return $this->storeFacade->getCurrentStore()->getName() . '_' . getenv('TENANT_IDENTIFIER');
    }
}
