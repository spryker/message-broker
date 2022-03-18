<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageValidator;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\MessageBroker\Business\StoreReferenceReceiver\StoreReferenceReceiverInterface;

class StoreReferenceMessageValidator implements MessageValidatorInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const VALIDATION_ERROR_STORE_REFERENCE_ERROR = 'Invalid storeReference in message "%s"';

    /**
     * @var \Spryker\Zed\MessageBroker\Business\StoreReferenceReceiver\StoreReferenceReceiverInterface
     */
    protected StoreReferenceReceiverInterface $storeReferenceReceiver;

    /**
     * @param \Spryker\Zed\MessageBroker\Business\StoreReferenceReceiver\StoreReferenceReceiverInterface $storeReferenceReceiver
     */
    public function __construct(StoreReferenceReceiverInterface $storeReferenceReceiver)
    {
        $this->storeReferenceReceiver = $storeReferenceReceiver;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return bool
     */
    public function isValid(TransferInterface $messageTransfer): bool
    {
        $storeReference = $this->storeReferenceReceiver->getStoreReference();
        if ($storeReference !== $messageTransfer->getMessageAttributes()->getStoreReference()) {
            $this->getLogger()->error(
                sprintf(static::VALIDATION_ERROR_STORE_REFERENCE_ERROR, get_class($messageTransfer)),
                [
                    'message' => $messageTransfer->toArray(),
                    'storeReference' => $storeReference,
                ],
            );

            return false;
        }

        return true;
    }
}
