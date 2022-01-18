<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\TenantIdentifierMessageAttributeProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group TenantIdentifierMessageAttributeProviderPluginTest
 * Add your own group annotations below this line
 */
class TenantIdentifierMessageAttributeProviderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TENANT_IDENTIFIER = 'foo';

    /**
     * @return void
     */
    public function testProvideMessageAttributesAddsTenantIdentifierWhenItExists(): void
    {
        // Arrange
        putenv(sprintf('AOP_TENANT_IDENTIFIER=%s', static::TENANT_IDENTIFIER));

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $tenantIdentifierMessageAttributeProviderPlugin = new TenantIdentifierMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer = $tenantIdentifierMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertSame(static::TENANT_IDENTIFIER, $messageAttributesTransfer->getTenantIdentifier());
        putenv('AOP_TENANT_IDENTIFIER');
    }

    /**
     * @return void
     */
    public function testProvideMessageAttributesDoesNotAddTenantIdentifierWhenItDoesNotExists(): void
    {
        // Arrange
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $tenantIdentifierMessageAttributeProviderPlugin = new TenantIdentifierMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer = $tenantIdentifierMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertNull($messageAttributesTransfer->getTenantIdentifier());
    }
}
