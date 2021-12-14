<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageHandlerPluginInterface;
use SprykerTest\Zed\MessageBroker\Messages\SomethingHappenedEvent;
use SprykerTest\Zed\MessageBroker\Messages\SomethingToDoCommand;

class SomethingHappenedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * @return void
     */
    public function handle(SomethingHappenedEvent $somethingHappenedEvent): void
    {
        $foo = 'bar';
    }

    /**
     * @return array<string>
     */
    public function handles(): array
    {
        return [
            SomethingHappenedEvent::class => [$this, 'handle'],
            SomethingToDoCommand::class,
        ];
    }
}
