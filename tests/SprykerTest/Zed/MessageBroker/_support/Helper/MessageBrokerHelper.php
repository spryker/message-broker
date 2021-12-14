<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Helper;

use AsyncAws\Sns\SnsClient;
use AsyncAws\Sqs\SqsClient;
use Codeception\Module;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\Receiver\AwsSqsMessageReceiverPlugin;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\Sender\AwsSnsMessageSenderPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageSenderPluginInterface;
use SprykerTest\Zed\MessageBroker\_support\Subscriber\StopWorkerWhenMessagesAreHandledEventDispatcherSubscriberPlugin;
use SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester;
use SprykerTest\Zed\MessageBroker\Plugin\InMemoryMessageTransportPlugin;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\DependencyProviderHelperTrait;
use Symfony\Component\Messenger\Bridge\AmazonSqs\Transport\AmazonSqsReceiver;
use Symfony\Component\Messenger\Bridge\AmazonSqs\Transport\Connection;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\EventListener\StopWorkerOnTimeLimitListener;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;
use Symfony\Component\Messenger\Transport\TransportInterface;

class MessageBrokerHelper extends Module
{
    use BusinessHelperTrait;
    use DependencyProviderHelperTrait;

    /**
     * @var \Symfony\Component\Messenger\Transport\Sender\SenderInterface|null
     */
    protected ?SenderInterface $sender = null;

    /**
     * @var \Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface|null
     */
    protected ?ReceiverInterface $receiver = null;

    /**
     * @var \Symfony\Component\Messenger\Transport\TransportInterface|\Symfony\Contracts\Service\ResetInterface|null
     */
    protected ?TransportInterface $transport = null;

    /**
     * @var \SprykerTest\Zed\MessageBroker\Plugin\InMemoryMessageTransportPlugin|null
     */
    protected ?InMemoryMessageTransportPlugin $transportPlugin = null;

    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester
     */
    protected ?MessageBrokerBusinessTester $tester = null;

    /**
     * @return \SprykerTest\Zed\MessageBroker\Plugin\InMemoryMessageTransportPlugin
     */
    public function getInMemoryMessageTransportPlugin(): InMemoryMessageTransportPlugin
    {
        if (!$this->transportPlugin) {
            $this->transport = new InMemoryTransport(new PhpSerializer());
            $this->transportPlugin = new InMemoryMessageTransportPlugin($this->transport);
        }

        return $this->transportPlugin;
    }

    /**
     * @return \Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageSenderPluginInterface
     */
    public function createSnsSenderPlugin(): MessageSenderPluginInterface
    {
        $snsClient = new SnsClient([
            'endpoint' => 'http://localhost.localstack.cloud:4566',
            'accessKeyId' => 'test',
            'accessKeySecret' => 'test',
            'region' => 'eu-central-1',
            'debug' => true,
        ]);

        return $this->sender = new AwsSnsMessageSenderPlugin($snsClient, new PhpSerializer(), 'arn:aws:sns:eu-central-1:000000000000:message-broker');
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Communication\Plugin\Receiver\AwsSqsMessageReceiverPlugin
     */
    public function createAwsSqsReceiverPlugin(): AwsSqsMessageReceiverPlugin
    {
        $sqsClient = new SqsClient([
            'endpoint' => 'http://localhost.localstack.cloud:4566',
            'accessKeyId' => 'test',
            'accessKeySecret' => 'test',
            'region' => 'eu-central-1',
        ]);

        $connection = new Connection([
            'endpoint' => 'http://localhost.localstack.cloud:4566',
            'access_key' => 'test',
            'secret_key' => 'test',
            'region' => 'eu-central-1',
            'queue_name' => 'message-broker',
        ], $sqsClient, 'http://localhost.localstack.cloud:4566/000000000000/message-broker');

        return $this->receiver = new AwsSqsMessageReceiverPlugin(new AmazonSqsReceiver($connection, new PhpSerializer()));
    }

    /**
     * @param string $messageName
     * @param string $headerName
     * @param callable|null $callable Use this to get content of the header in your test.
     *
     * @return void
     */
    public function assertMessageHasHeader(
        string $messageName,
        string $headerName,
        ?callable $callable = null
    ): void {
        if (!$this->transportPlugin) {
            codecept_debug(sprintf('"%s" can only be used when the "%s" plugin is used.', __METHOD__, InMemoryMessageTransportPlugin::class));

            return;
        }

        $message = $this->getMessageByName($messageName);
        $this->assertNotNull($message, sprintf('Message "%s" was not sent.', $messageName));

        $stamp = $message->last($headerName);
        $this->assertNotNull($stamp, sprintf('Message "%s" does not have the header "%s".', $messageName, $headerName));

        if ($callable) {
            $callable($stamp);
        }
    }

    /**
     * @param string $messageName
     *
     * @return \Symfony\Component\Messenger\Envelope|null
     */
    protected function getMessageByName(string $messageName): ?Envelope
    {
        if (!method_exists($this->transport, 'getSent')) {
            codecept_debug(sprintf('"%s" can only be used when the "%s" plugin is used.', __METHOD__, InMemoryMessageTransportPlugin::class));

            return null;
        }

        foreach ($this->transport->getSent() as $key => $message) {
            $innerMessage = $message->getMessage();
            if ($innerMessage instanceof $messageName) {
                return $message;
            }
        }

        return null;
    }

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageReceiverPluginInterface> $messageReceiverPlugins
     *
     * @return void
     */
    public function setMessageReceiverPlugins(array $messageReceiverPlugins)
    {
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_RECEIVER, $messageReceiverPlugins);
    }

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageSenderPluginInterface> $messageSenderPlugins
     *
     * @return void
     */
    public function setMessageSenderPlugins(array $messageSenderPlugins)
    {
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_SENDER, $messageSenderPlugins);
    }

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageHandlerPluginInterface> $messageHandlerPlugins
     *
     * @return void
     */
    public function setMessageHandlerPlugins(array $messageHandlerPlugins)
    {
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_HANDLER, $messageHandlerPlugins);
    }

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageDecoratorPluginInterface> $messageDecoratorPlugins
     *
     * @return void
     */
    public function setMessageDecoratorPlugins(array $messageDecoratorPlugins)
    {
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_DECORATOR, $messageDecoratorPlugins);
    }

    /**
     * @return void
     */
    public function consumeMessages()
    {
        // Add Event subscriber that will stop the Worker when all messages are handled or when a time limit was reached.
        // This prevents the worker from running forever.
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_EVENT_DISPATCHER, [
            new StopWorkerWhenMessagesAreHandledEventDispatcherSubscriberPlugin(),
            new StopWorkerOnTimeLimitListener(10),
        ]);

        $this->getFactory()->createWorker()->run();
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Business\MessageBrokerBusinessFactory
     */
    protected function getFactory()
    {
        /** @var \Spryker\Zed\MessageBroker\Business\MessageBrokerBusinessFactory $messageBrokerFactory */
        $messageBrokerFactory = $this->getBusinessHelper()->getFactory();

        return $messageBrokerFactory;
    }
}
