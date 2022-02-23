<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\StoreReferenceMessageAttributeProviderPlugin;
use Spryker\Zed\MessageBroker\Dependency\MessageBrokerToStoreBridge;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group StoreReferenceMessageAttributeProviderPluginTest
 * Add your own group annotations below this line
 */
class StoreReferenceMessageAttributeProviderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TENANT_IDENTIFIER = 'foo';

    /**
     * @var string
     */
    protected const STORE_NAME = 'boo';

    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester
     */
    protected MessageBrokerCommunicationTester $tester;

    /**
     * @return void
     */
    public function testProvideMessageAttributesAddsStoreReferenceWhenItExists(): void
    {
        // Arrange
        putenv(sprintf('TENANT_IDENTIFIER=%s', static::TENANT_IDENTIFIER));

        $this->mockStoreFacadeDefaultStore();

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $storeReferenceMessageAttributeProviderPlugin = new StoreReferenceMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer = $storeReferenceMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertSame(static::STORE_NAME . '_' . static::TENANT_IDENTIFIER, $messageAttributesTransfer->getStoreReference());
        putenv('TENANT_IDENTIFIER');
    }

    /**
     * @return void
     */
    public function testProvideMessageAttributesDoesNotAddStoreReferenceWhenTenantIdentifierDoesNotExists(): void
    {
        // Arrange
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $storeReferenceMessageAttributeProviderPlugin = new StoreReferenceMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer = $storeReferenceMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertNull($messageAttributesTransfer->getStoreReference());
    }

    /**
     * @return void
     */
    protected function mockStoreFacadeDefaultStore(): void
    {
        $storeFacadeMock = $this->getMockBuilder(MessageBrokerToStoreBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $storeFacadeMock->method('getCurrentStore')->willReturn(
            (new StoreTransfer())->setName(static::STORE_NAME),
        );

        $this->tester->setDependency(MessageBrokerDependencyProvider::FACADE_STORE, $storeFacadeMock);
    }
}
