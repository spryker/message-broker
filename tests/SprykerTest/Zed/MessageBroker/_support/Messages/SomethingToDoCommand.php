<?php

namespace SprykerTest\Zed\MessageBroker\Messages;

class SomethingToDoCommand
{
    protected array $payload = [];

    /**
     * @param string|null $message
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
