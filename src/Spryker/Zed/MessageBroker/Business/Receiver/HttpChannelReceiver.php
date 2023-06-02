<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Receiver;

use Monolog\Logger;
use Spryker\Zed\MessageBroker\Dependency\Guzzle\MessageBrokerToGuzzleClientInterface;

class HttpChannelReceiver implements HttpChannelReceiverInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Dependency\Guzzle\MessageBrokerToGuzzleClientInterface
     */
    protected $httpClient;

    public function __construct(MessageBrokerToGuzzleClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function receive()
    {
        $this->httpClient->send();
    }
}
