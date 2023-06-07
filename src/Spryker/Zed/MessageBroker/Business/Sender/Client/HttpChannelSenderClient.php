<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Sender\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface;
use Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBroker\Business\Receiver\Stamp\ChannelNameStamp;
use Spryker\Zed\MessageBroker\Business\Sender\Client\Formatter\HttpHeaderFormatterInterface;
use Spryker\Zed\MessageBroker\Business\Sender\Client\Stamp\SenderClientStamp;
use Spryker\Zed\MessageBroker\Dependency\Guzzle\MessageBrokerToGuzzleClientInterface;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Throwable;

class HttpChannelSenderClient implements SenderClientInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\MessageBrokerConfig
     */
    protected MessageBrokerConfig $config;

    /**
     * @var \Spryker\Zed\MessageBroker\Dependency\Guzzle\MessageBrokerToGuzzleClientInterface
     */
    protected $httpClient;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface
     */
    protected $messageBrokerAwsFacade;

    /**
     * @var \Spryker\Zed\MessageBroker\Business\Sender\Client\Formatter\HttpHeaderFormatterInterface
     */
    protected $headerFormatter;

    /**
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $config
     * @param \Spryker\Zed\MessageBroker\Dependency\Guzzle\MessageBrokerToGuzzleClientInterface $httpClient
     * @param \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface $messageBrokerAwsFacade
     * @param \Spryker\Zed\MessageBroker\Business\Sender\Client\Formatter\HttpHeaderFormatterInterface $headerFormatter
     */
    public function __construct(
        MessageBrokerConfig $config,
        MessageBrokerToGuzzleClientInterface $httpClient,
        MessageBrokerAwsFacadeInterface $messageBrokerAwsFacade,
        HttpHeaderFormatterInterface $headerFormatter,
    ) {
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->messageBrokerAwsFacade = $messageBrokerAwsFacade;
        $this->headerFormatter = $headerFormatter;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Symfony\Component\Messenger\Exception\TransportException
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        $channelNameStamp = $envelope->last(ChannelNameStamp::class);
        $encodedMessage = $this->messageBrokerAwsFacade->serializeEnvelope($envelope);
        $headers = $this->headerFormatter->formatHeaders($encodedMessage['headers'] ?? []);
        $body = '"' . addcslashes(json_encode($encodedMessage['bodyRaw']), '"') . '"';

        $response = $this->httpClient->request(
            SymfonyRequest::METHOD_POST,
            $this->config->getProducerGatewayUrl() . $channelNameStamp->getChannelName(),
            [
                RequestOptions::HEADERS => ['Content-Type' => 'application/json'] + $headers,
                RequestOptions::BODY => $body,
            ],
        );

        return $envelope->with(new SenderClientStamp(static::class));
    }
}
