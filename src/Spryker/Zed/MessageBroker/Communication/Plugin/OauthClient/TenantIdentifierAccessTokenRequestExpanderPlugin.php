<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\OauthClient;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthClientExtension\Dependency\Plugin\AccessTokenRequestExpanderPluginInterface;

class TenantIdentifierAccessTokenRequestExpanderPlugin extends AbstractPlugin implements AccessTokenRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `AccessTokenRequest.accessTokenRequestOptions` with tenant_identifier property with SPRYKER_TENANT_IDENTIFIER environment variable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @throws \Spryker\Zed\Store\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expand(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer
    {
        $accessTokenRequestOptionsTransfer = $accessTokenRequestTransfer->getAccessTokenRequestOptions();
        if ($accessTokenRequestOptionsTransfer === null) {
            $accessTokenRequestOptionsTransfer = new AccessTokenRequestOptionsTransfer();
        }
        $accessTokenRequestOptionsTransfer->setTenantIdentifier(getenv('SPRYKER_TENANT_IDENTIFIER'));

        $accessTokenRequestTransfer->setAccessTokenRequestOptions($accessTokenRequestOptionsTransfer);

        return $accessTokenRequestTransfer;
    }
}
