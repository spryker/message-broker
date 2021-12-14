<?php

namespace Spryker\Zed\MessageBroker\Communication\Plugin\MessageDecorator;

use Ramsey\Uuid\Uuid;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependecy\Plugin\MessageDecoratorPluginInterface;

class CorrelationIdMessageDecoratorPlugin extends AbstractPlugin implements MessageDecoratorPluginInterface
{
    /**
     * @var string|null
     */
    protected ?string $correlationId = null;

    public function __construct()
    {
        $this->correlationId = Uuid::uuid4()->toString();
    }

    /**
     * The `correlationId` will be added to messages. It MUST be unique for one request to be able to debug messages for a given request.
     * All messages sent during this request will have the same `correlationId`.
     *
     * @return string
     */
    public function getCorrelationId(): string
    {
        return $this->correlationId;
    }
}
