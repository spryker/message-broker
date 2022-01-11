<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageDecorator;

use Symfony\Component\Messenger\Envelope;

class MessageDecorator implements MessageDecoratorInterface
{
    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageDecoratorPluginInterface>
     */
    protected array $messageDecoratorPlugins = [];

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageDecoratorPluginInterface> $messageDecoratorPlugins
     */
    public function __construct(array $messageDecoratorPlugins)
    {
        $this->messageDecoratorPlugins = $messageDecoratorPlugins;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $message
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function decorateMessage(Envelope $message): Envelope
    {
        foreach ($this->messageDecoratorPlugins as $messageDecoratorPlugin) {
            $message = $messageDecoratorPlugin->decorateMessage($message);
        }

        return $message;
    }
}
