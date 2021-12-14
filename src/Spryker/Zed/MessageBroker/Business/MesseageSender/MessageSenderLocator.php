<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MesseageSender;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;

class MessageSenderLocator implements SendersLocatorInterface
{
    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageSenderPluginInterface>
     */
    protected array $messageSenderPlugins = [];

    /**
     * @var array
     */
    protected array $namedMessageSenderPlugins = [];

    /**
     * @param array $messageSenderPlugins
     */
    public function __construct(array $messageSenderPlugins)
    {
        $this->messageSenderPlugins = $messageSenderPlugins;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return iterable
     */
    public function getSenders(Envelope $envelope): iterable
    {
        return $this->getNamedMessageSender();
    }

    /**
     * @return array
     */
    protected function getNamedMessageSender(): array
    {
        if (!$this->namedMessageSenderPlugins) {
            foreach ($this->messageSenderPlugins as $messageSenderPlugin) {
                $this->namedMessageSenderPlugins[$messageSenderPlugin->getChannelName()] = $messageSenderPlugin;
            }
        }

        return $this->namedMessageSenderPlugins;
    }
}
