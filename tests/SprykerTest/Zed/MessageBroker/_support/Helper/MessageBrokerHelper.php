<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Decorator\Receiver\AwsSqsMessageReceiverPlugin;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Sender\AwsSnsMessageSenderPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageReceiverPluginInterface;
use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageSenderPluginInterface;
use SprykerTest\Zed\MessageBroker\_support\Subscriber\StopWorkerWhenMessagesAreHandledEventDispatcherSubscriberPlugin;
use SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester;
use SprykerTest\Zed\MessageBroker\Plugin\InMemoryMessageTransportPlugin;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\DependencyProviderHelperTrait;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\EventListener\StopWorkerOnTimeLimitListener;
use Symfony\Component\Messenger\Stamp\SentStamp;
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
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);

        putenv('AOP_MESSAGE_TO_SENDER_CHANNEL_MAP');
        putenv('AOP_SENDER_CHANNEL_TO_CLIENT_MAP');
        putenv('AOP_MESSAGE_BROKER_SNS_SENDER');
        putenv('AOP_MESSAGE_BROKER_SQS_RECEIVER');
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param string $senderAlias
     *
     * @return void
     */
    public function assertMessageWasSentWithSender(Envelope $envelope, string $senderAlias): void
    {
        /** @var \Symfony\Component\Messenger\Stamp\SentStamp $sentStamp */
        $sentStamp = $envelope->last(SentStamp::class);

        // Assert
        $this->assertNotNull($sentStamp, sprintf('Expected to have a "%s" stamp but it was not found.', SentStamp::class));
        $this->assertSame('in-memory', $sentStamp->getSenderAlias(), sprintf('Expected that message was sent with the "in-memory" sender but was sent with "%s".', $sentStamp->getSenderAlias() ?? ''));
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param array $senderAlias
     *
     * @return void
     */
    public function assertMessageWasSentWithSenders(Envelope $envelope, array $senderAlias): void
    {
        /** @var \Symfony\Component\Messenger\Stamp\SentStamp $sentStamp */
        $sentStamps = $envelope->all(SentStamp::class);

        // Assert
        $this->assertNotNull($sentStamps, sprintf('Expected to have a "%s" stamp but it was not found.', SentStamp::class));

        foreach ($sentStamps as $sentStamp) {
            $stampSenderAlias = $sentStamp->getSenderAlias();
            $this->assertTrue(in_array($stampSenderAlias, $senderAlias), sprintf('Expected that message was sent with the "%s" but was not.', $stampSenderAlias));
        }
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param string $stampClass
     *
     * @return void
     */
    public function assertMessageHasStamp(Envelope $envelope, string $stampClass): void
    {
        $stamp = $envelope->last($stampClass);

        // Assert
        $this->assertNotNull($stamp, sprintf('Expected to have a "%s" stamp but it was not found.', $stampClass));
    }

    /**
     * @param string $messageClassName
     * @param string $channelName
     *
     * @return void
     */
    public function setMessageToSenderChannelNameMap(string $messageClassName, string $channelName): void
    {
        putenv(sprintf('AOP_MESSAGE_TO_SENDER_CHANNEL_MAP=%s=%s', $messageClassName, $channelName));
    }

    /**
     * @param string $channelName
     * @param string $clientName
     */
    public function setSenderChannelToClientNameMap(string $channelName, string $clientName): void
    {
        putenv(sprintf('AOP_SENDER_CHANNEL_TO_CLIENT_MAP=%s=%s', $channelName, $clientName));
    }

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
         putenv('AOP_MESSAGE_BROKER_SNS_SENDER=endpoint=http://localhost.localstack.cloud:4566&accessKeyId=test&accessKeySecret=test&region=eu-central-1&topic=arn:aws:sns:eu-central-1:000000000000:message-broker');
//        $snsClient = new SnsClient([
//            'endpoint' => 'http://localhost.localstack.cloud:4566',
//            'accessKeyId' => 'test',
//            'accessKeySecret' => 'test',
//            'region' => 'eu-central-1',
//            'debug' => true,
//        ]);

        return $this->sender = new AwsSnsMessageSenderPlugin();
//        return $this->sender = new AwsSnsMessageSenderPlugin($snsClient, new PhpSerializer(), 'arn:aws:sns:eu-central-1:000000000000:message-broker');
    }

    /**
     * @return \Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface
     */
    public function createAwsSqsReceiverPlugin(): MessageReceiverPluginInterface
    {
        putenv('AOP_MESSAGE_BROKER_SQS_RECEIVER=endpoint=http://localhost.localstack.cloud:4566&accessKeyId=test&accessKeySecret=test&region=eu-central-1&queueName=message-broker');
//        $sqsClient = new SqsClient([
//            'endpoint' => 'http://localhost.localstack.cloud:4566',
//            'accessKeyId' => 'test',
//            'accessKeySecret' => 'test',
//            'region' => 'eu-central-1',
//        ]);
//
//        $connection = new Connection([
//            'queue_name' => 'message-broker',
//        ], $sqsClient);

        return $this->receiver = new AwsSqsMessageReceiverPlugin();
//        return $this->receiver = new AwsSqsMessageReceiverPlugin(new AmazonSqsReceiver($connection, new PhpSerializer()));
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
