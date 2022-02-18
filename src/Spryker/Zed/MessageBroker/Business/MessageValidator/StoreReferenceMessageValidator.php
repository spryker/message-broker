<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageValidator;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\MessageBroker\Business\StoreReferenceBuilder\StoreReferenceBuilderInterface;

class StoreReferenceMessageValidator implements MessageValidatorInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const VALIDATION_ERROR_STORE_REFERENCE_ERROR = 'Invalid storeReference in message for current queue.';

    /**
     * @var \Spryker\Zed\MessageBroker\Business\StoreReferenceBuilder\StoreReferenceBuilderInterface
     */
    protected $storeReferenceBuilder;

    /**
     * @param \Spryker\Zed\MessageBroker\Business\StoreReferenceBuilder\StoreReferenceBuilderInterface $storeReferenceBuilder
     */
    public function __construct(StoreReferenceBuilderInterface $storeReferenceBuilder)
    {
        $this->storeReferenceBuilder = $storeReferenceBuilder;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $message
     *
     * @return bool
     */
    public function isValidMessage(TransferInterface $message): bool
    {
        $storeReference = $this->storeReferenceBuilder->buildStoreReference();
        if ($storeReference !== $message->getMessageAttributes()->getStoreReference()) {
            $this->getLogger()->error(static::VALIDATION_ERROR_STORE_REFERENCE_ERROR, [
                'message' => $message->toArray(),
                'storeReference' => $storeReference,
            ]);

            return false;
        }

        return true;
    }
}
