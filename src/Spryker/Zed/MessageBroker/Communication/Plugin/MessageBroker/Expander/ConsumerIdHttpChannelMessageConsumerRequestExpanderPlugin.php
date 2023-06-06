<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\Expander;

use Generated\Shared\Transfer\HttpRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\HttpChannelMessageConsumerRequestExpanderPluginInterface;

class ConsumerIdHttpChannelMessageConsumerRequestExpanderPlugin extends AbstractPlugin implements HttpChannelMessageConsumerRequestExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HttpRequestTransfer
     */
    public function expand(HttpRequestTransfer $httpRequestTransfer): HttpRequestTransfer
    {
        $httpRequestTransfer->setConsumerId(getenv('SPRYKER_TENANT_IDENTIFIER'));

        return $httpRequestTransfer;
    }
}
