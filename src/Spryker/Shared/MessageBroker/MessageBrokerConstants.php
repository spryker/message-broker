<?php

namespace Spryker\Shared\MessageBroker;

interface MessageBrokerConstants
{
    /**
     * @var string
     */
    public const MESSAGE_TO_CHANNEL_MAP = 'MESSAGE_BROKER:MESSAGE_TO_CHANNEL_MAP';

    /**
     * @var string
     */
    public const MESSAGE_TO_SENDER_CHANNEL_MAP = 'MESSAGE_BROKER:MESSAGE_TO_SENDER_CHANNEL_MAP';

    /**
     * @var string
     */
    public const SENDER_CHANNEL_TO_CLIENT_MAP = 'MESSAGE_BROKER:SENDER_CHANNEL_TO_CLIENT_MAP';
}
