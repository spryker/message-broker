<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Spryker\Zed\Event\EventDependencyProvider;
use Spryker\Zed\MessageBroker\Business\MessageBrokerBusinessFactory;
use Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface;
use Spryker\Zed\MessageBroker\Business\Worker\Worker;
use Spryker\Zed\MessageBroker\Communication\Plugin\Console\MessageBrokerWorkerConsole;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Receiver\AwsSqsMessageReceiverPlugin;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Sender\AwsSnsMessageSenderPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface;
use SprykerTest\Zed\Console\Helper\ConsoleHelperTrait;
use SprykerTest\Zed\MessageBroker\_support\Subscriber\StopWorkerWhenMessagesAreHandledEventDispatcherSubscriberPlugin;
use SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester;
use SprykerTest\Zed\MessageBroker\Plugin\InMemoryMessageTransportPlugin;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\DependencyProviderHelperTrait;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\EventListener\StopWorkerOnTimeLimitListener;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;
use Symfony\Component\Messenger\Transport\TransportInterface;

class InMemoryMessageBrokerHelper extends Module
{
    use BusinessHelperTrait;
    use DependencyProviderHelperTrait;

    /**
     * @var InMemoryTransport|null
     */
    protected ?InMemoryTransport $transport;

    /**
     * @var \SprykerTest\Zed\MessageBroker\Plugin\InMemoryMessageTransportPlugin|null
     */
    protected ?InMemoryMessageTransportPlugin $transportPlugin;

    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester
     */
    protected MessageBrokerBusinessTester $tester;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $this->transport = null;
        $this->transportPlugin = null;

        putenv('AOP_MESSAGE_TO_CHANNEL_MAP');
        putenv('AOP_SENDER_CHANNEL_TO_CLIENT_MAP');

        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_SENDER, [$this->getInMemoryMessageTransportPlugin()]);
    }

    /**
     * Setup the MessageBroker to use the InMemory Transport for a specific message and the channel name the message will use.
     *
     * @param string $messageClassName The transfer class name
     * @param string $channelName The channel name we will use for processing
     *
     * @return void
     */
    public function setupMessageBroker(string $messageClassName, string $channelName): void
    {
        putenv(sprintf('AOP_MESSAGE_TO_CHANNEL_MAP={"%s": "%s"}', str_replace('\\', '\\\\', $messageClassName), $channelName));
        putenv(sprintf('AOP_SENDER_CHANNEL_TO_CLIENT_MAP={"%s": "in-memory"}', $channelName));
    }

    /**
     * @param string $messageName
     */
    public function assertMessageWasSent(string $messageName): void
    {
        $envelope = $this->getMessageByName($messageName);

        $this->assertNotNull($envelope, sprintf('Expected to have a messsage with class name "%s" sent, but it was not found.'));
    }

    /**
     * @return \SprykerTest\Zed\MessageBroker\Plugin\InMemoryMessageTransportPlugin
     */
    protected function getInMemoryMessageTransportPlugin(): InMemoryMessageTransportPlugin
    {
        if (!$this->transportPlugin) {
            $this->transport = new InMemoryTransport(new PhpSerializer());
            $this->transportPlugin = new InMemoryMessageTransportPlugin($this->transport);
        }

        return $this->transportPlugin;
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

        foreach ($this->transport->getSent() as $message) {
            $innerMessage = $message->getMessage();
            if ($innerMessage instanceof $messageName) {
                return $message;
            }
        }

        return null;
    }
}
