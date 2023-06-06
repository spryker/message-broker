<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Receiver;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\HttpChannelMessageTransfer;
use Generated\Shared\Transfer\HttpRequestTransfer;
use GuzzleHttp\RequestOptions;
use Monolog\Logger;
use Spryker\Zed\MessageBroker\Dependency\Guzzle\MessageBrokerToGuzzleClientInterface;
use Spryker\Zed\MessageBroker\Dependency\Oauth\MessageBrokerToOauthClientInterface;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class HttpChannelReceiver implements HttpChannelReceiverInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\MessageBrokerConfig
     */
    protected $messageBrokerConfig;

    /**
     * @var \Spryker\Zed\MessageBroker\Dependency\Guzzle\MessageBrokerToGuzzleClientInterface
     */
    protected $httpClient;

    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\HttpChannelMessageConsumerRequestExpanderPluginInterface>
     */
    protected $httpChannelExpanderPlugins;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface
     */
    protected $messageBrokerAwsFacade;

    /**
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $messageBrokerConfig
     * @param \Spryker\Zed\MessageBroker\Dependency\Guzzle\MessageBrokerToGuzzleClientInterface $httpClient
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\HttpChannelMessageConsumerRequestExpanderPluginInterface> $httpChannelExpanderPlugins
     * @param \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface $messageBrokerAwsFacade
     */
    public function __construct(
        MessageBrokerConfig $messageBrokerConfig,
        MessageBrokerToGuzzleClientInterface $httpClient,
        array $httpChannelExpanderPlugins,
        MessageBrokerAwsFacadeInterface $messageBrokerAwsFacade
    ) {
        $this->messageBrokerConfig = $messageBrokerConfig;
        $this->httpClient = $httpClient;
        $this->httpChannelExpanderPlugins = $httpChannelExpanderPlugins;
        $this->messageBrokerAwsFacade = $messageBrokerAwsFacade;
    }

    /**
     * @param array<string> $channels
     *
     * @return array<\Symfony\Component\Messenger\Envelope>
     */
    public function receive(array $channels): iterable
    {
        $httpRequestTransfer = $this->getHttpRequestTransfer();

        $envelopes = [];
        foreach ($channels as $channel) {
            $response = $this->httpClient->request(
                Request::METHOD_GET,
                $this->messageBrokerConfig->getConsumerGatewayUrl() . $channel,
                [
                    'limit' => $this->messageBrokerConfig->getMessageConsumeLimit(),
                    RequestOptions::HEADERS => [
                        'consumer-id' => $httpRequestTransfer->getConsumerId(),
                        'authorization' => $httpRequestTransfer->getAuthorization(),
                    ],
                ],
            );

            $messages = json_decode($response->getBody()->getContents(), true);
            if (!$messages) {
                continue;
            }

            $envelopes = array_merge($envelopes, $this->transformMessagesToEnvelopes($messages));
        }

        return $envelopes;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param array $channels
     *
     * @return void
     */
    public function delete(Envelope $envelope, $channels): void
    {
        $httpRequestTransfer = $this->getHttpRequestTransfer();
        $messageId = $envelope->getMessage()->getMessageId();

        foreach ($channels as $channel) {
            $body = json_encode([
                "messageIds" => [$messageId]
            ]);
            $response = $this->httpClient->request(
                Request::METHOD_DELETE,
                $this->messageBrokerConfig->getConsumerGatewayUrl() . $channel,
                [
                    RequestOptions::HEADERS => [
                        'consumer-id' => $httpRequestTransfer->getConsumerId(),
                        'authorization' => $httpRequestTransfer->getAuthorization(),
                        'Content-Type' => 'application/json',
                    ],
                    RequestOptions::BODY => $body,
                ],
            );
        }
    }

    /**
     * @return \Generated\Shared\Transfer\HttpRequestTransfer
     */
    protected function getHttpRequestTransfer(): HttpRequestTransfer
    {
        $httpRequestTransfer = new HttpRequestTransfer();
        foreach ($this->httpChannelExpanderPlugins as $expanderPlugin){
            $httpRequestTransfer = $expanderPlugin->expand($httpRequestTransfer);
        }

        return $httpRequestTransfer;
    }

    /**
     * @param array $messages
     *
     * @return array
     */
    protected function transformMessagesToEnvelopes(array $messages): array
    {
        $envelopes = [];
        foreach ($messages as $message) {
            $message['MessageAttributes']['transferName'] = 'HttpChannelMessage';

            $envelopeData = [
                'body' => json_encode([
                    'message' => $message['MessageBody'],
                    'messageId' => $message['MessageId'],
                ]),
                'headers' => $message['MessageAttributes'],
            ];
            $envelopes[] = $this->messageBrokerAwsFacade->createEnvelope($envelopeData);
        }

        return $envelopes;
    }
}
