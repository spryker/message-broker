<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Dependency\Oauth;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Generated\Shared\Transfer\HttpRequestTransfer;

interface MessageBrokerToOauthClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function getAccessToken(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\HttpRequestTransfer
     */
    public function expandHttpRequest(HttpRequestTransfer $httpRequestTransfer): HttpRequestTransfer;
}
