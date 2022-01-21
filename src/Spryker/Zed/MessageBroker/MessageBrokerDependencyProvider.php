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
     * @uses \Spryker\Zed\EventDispatcher\Communication\Plugin\Application\EventDispatcherApplicationPlugin::SERVICE_DISPATCHER
     *
     * @var string
     */
    public const SERVICE_EVENT_DISPATCHER = 'dispatcher';

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
    public const PLUGINS_MESSAGE_ATTRIBUTE_PROVIDER = 'PLUGINS_MESSAGE_ATTRIBUTE_PROVIDER';

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
        $container = $this->provideMessageAttributeProviderPlugins($container);

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
            return $this->getMessageSenderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    public function getMessageSenderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMessageReceiverAdapterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MESSAGE_RECEIVER, function () {
            return $this->getMessageReceiverPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface>
     */
    public function getMessageReceiverPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMessageHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MESSAGE_HANDLER, function () {
            return $this->getMessageHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface>
     */
    public function getMessageHandlerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMessageAttributeProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MESSAGE_ATTRIBUTE_PROVIDER, function () {
            return $this->getMessageAttributeProviderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageAttributeProviderPluginInterface>
     */
    public function getMessageAttributeProviderPlugins(): array
    {
        return [];
    }
}
