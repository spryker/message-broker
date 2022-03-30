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
use Spryker\Zed\MessageBroker\Dependency\Facade\MessageBrokerToStoreFacadeBridge;
use Spryker\Zed\MessageBroker\Dependency\Facade\MessageBrokerToStoreReferenceFacadeBridge;
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
    protected const STORE_NAME_REFERENCE_MAP = '{"boo":"development_test-boo","foo":"development_test-foo"}';

    /**
     * @var string
     */
    protected const STORE_NAME = 'boo';

    /**
     * @var string
     */
    protected const STORE_REFERENCE_NAME = 'development_test-boo';

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
        putenv(sprintf('STORE_NAME_REFERENCE_MAP=%s', static::STORE_NAME_REFERENCE_MAP));

        $this->mockStoreFacadeDefaultStore();
        $this->mockStoreReferenceFacadeDefaultStore();

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $storeReferenceMessageAttributeProviderPlugin = new StoreReferenceMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer = $storeReferenceMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertSame(static::STORE_REFERENCE_NAME, $messageAttributesTransfer->getStoreReference());
        putenv('STORE_NAME_REFERENCE_MAP');
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
        $storeFacadeMock = $this->getMockBuilder(MessageBrokerToStoreFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $storeFacadeMock->method('getCurrentStore')->willReturn(
            (new StoreTransfer())->setName(static::STORE_NAME),
        );

        $this->tester->setDependency(MessageBrokerDependencyProvider::FACADE_STORE, $storeFacadeMock);
    }

    /**
     * @return void
     */
    protected function mockStoreReferenceFacadeDefaultStore(): void
    {
        $storeFacadeReferenceMock = $this->getMockBuilder(MessageBrokerToStoreReferenceFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $storeFacadeReferenceMock->method('getStoreByStoreName')->willReturn(
            (new StoreTransfer())->setStoreReference(static::STORE_REFERENCE_NAME),
        );

        $this->tester->setDependency(MessageBrokerDependencyProvider::FACADE_STORE_REFERENCE, $storeFacadeReferenceMock);
    }
}
