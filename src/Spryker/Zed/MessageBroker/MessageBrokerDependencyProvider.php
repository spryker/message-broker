<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\MessageBroker\MessageBrokerConfig getConfig()
 */
class MessageBrokerDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_MESSAGE_SENDER = 'PLUGINS_MESSAGE_SENDER';

    /**
     * @var string
     */
    public const PLUGINS_MESSAGE_RECEIVER = 'PLUGINS_MESSAGE_RECEIVER';

    /**
     * @var string
     */
    public const PLUGINS_MESSAGE_HANDLER = 'PLUGINS_MESSAGE_HANDLER';

    /**
     * @var string
     */
    public const PLUGINS_MESSAGE_DECORATOR = 'PLUGINS_MESSAGE_DECORATOR';

    /**
     * @var string
     */
    public const PLUGINS_EVENT_DISPATCHER = 'PLUGINS_EVENT_DISPATCHER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->provideMessageSenderAdapterPlugins($container);
        $container = $this->provideMessageReceiverAdapterPlugins($container);
        $container = $this->provideMessageHandlerPlugins($container);
        $container = $this->provideMessageDecoratorPlugins($container);
        $container = $this->provideEventDispatcherSubscriberPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMessageSenderAdapterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MESSAGE_SENDER, function () {
            return [];
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMessageReceiverAdapterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MESSAGE_RECEIVER, function () {
            return [];
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMessageHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MESSAGE_HANDLER, function () {
            return [];
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMessageDecoratorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MESSAGE_DECORATOR, function () {
            return [];
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideEventDispatcherSubscriberPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_EVENT_DISPATCHER, function () {
            return [];
        });

        return $container;
    }
}