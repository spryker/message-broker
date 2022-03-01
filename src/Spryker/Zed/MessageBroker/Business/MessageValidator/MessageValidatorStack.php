<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageValidator;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

class MessageValidatorStack implements MessageValidatorStackInterface
{
    /**
     * @var array<\Spryker\Zed\MessageBroker\Business\MessageValidator\MessageValidatorInterface>
     */
    protected array $messageValidators;

    /**
     * @param array<\Spryker\Zed\MessageBroker\Business\MessageValidator\MessageValidatorInterface> $messageValidators
     */
    public function __construct(array $messageValidators)
    {
        $this->messageValidators = $messageValidators;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return bool
     */
    public function isValidMessage(TransferInterface $messageTransfer): bool
    {
        $isValidMessage = true;
        foreach ($this->messageValidators as $messageValidator) {
            $isValidMessage = $messageValidator->isValid($messageTransfer);

            if (!$isValidMessage) {
                $isValidMessage = false;
            }
        }

        return $isValidMessage;
    }
}
