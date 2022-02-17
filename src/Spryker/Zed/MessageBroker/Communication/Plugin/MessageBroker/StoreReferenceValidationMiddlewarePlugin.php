<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class StoreReferenceValidationMiddlewarePlugin extends AbstractPlugin implements MiddlewareInterface
{
    use LoggerTrait;
    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param \Symfony\Component\Messenger\Middleware\StackInterface $stack
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $storeReference = $this->getStoreReference();
        if ($envelope->getMessage()->getMessageAttributes()->getStoreReference() !== $storeReference) {
            $this->getLogger()->error('Invalid storeReference in message for current queue.', [
                'message' => $envelope->getMessage()->toArray(),
                'storeReference' => $storeReference,
            ]);

            throw new UnrecoverableMessageHandlingException();
        }

        return $stack->next()->handle($envelope, $stack);
    }

    /**
     * @return string
     */
    public function getStoreReference(): string
    {
        $store = Store::getInstance()->getStoreName();
        $tenantIdentifier = getenv('TENANT_IDENTIFIER');

        return $store . '_' . $tenantIdentifier;
    }
}