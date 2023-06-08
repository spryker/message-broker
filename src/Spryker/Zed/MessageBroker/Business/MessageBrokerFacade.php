<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;

/**
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerBusinessFactory getFactory()
 */
class MessageBrokerFacade extends AbstractFacade implements MessageBrokerFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageResponseTransfer
     */
    public function sendMessage(TransferInterface $messageTransfer): MessageResponseTransfer
    {
        return $this->getFactory()->createMessagePublisher()->sendMessage($messageTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer
     *
     * @return void
     */
    public function startWorker(MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer): void
    {
        $this->getFactory()->createWorker()->runWorker($messageBrokerWorkerConfigTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string|null $pathToAsyncApiFile
     *
     * @return void
     */
    public function printDebug(OutputInterface $output, ?string $pathToAsyncApiFile = null): void
    {
        $this->getFactory()->createDebugPrinter()->printDebug($output, $pathToAsyncApiFile);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $message
     *
     * @return bool
     */
    public function canHandleMessage(TransferInterface $message): bool
    {
        return $this->getFactory()->createMessageValidatorStack()->isValidMessage($message);
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     *
     * @api
     *
     * @param array<string> $channels
     *
     * @return array<\Symfony\Component\Messenger\Envelope>
     */
    public function getEnvelopes(array $channels): iterable
    {
        return $this->getFactory()->createHttpChannelReceiver()->receive($channels);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param array $channels
     *
     * @return void
     */
    public function deleteEnvelope(Envelope $envelope, array $channels): void
    {
        $this->getFactory()->createHttpChannelReceiver()->delete($envelope, $channels);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function sendEnvelopeWithHttpChannel(Envelope $envelope): Envelope
    {
        return $this->getFactory()->createHttpChannelSenderClient()->send($envelope);
    }
}
