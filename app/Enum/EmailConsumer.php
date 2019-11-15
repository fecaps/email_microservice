<?php
declare(strict_types=1);

namespace App\Enum;

class EmailConsumer
{
    public const DEFAULT_QUEUE = 'email_queue';
    public const DEFAULT_MAX_RETRIES = 5;
    public const DATA_KEY = 'data';
    public const RETRIES_KEY = 'retries';
    public const CONSUMER_START = 'Email consumer has started';
    public const CONSUMER_ACK = 'Email consumer has acknowledged a message';
    public const CONSUMER_NACK = 'Email consumer has not acknowledged a message';
    public const CONSUMER_RETRIES = 'Quantity of retries performed for a message';
    public const CONSUMER_REMOVED = 'Email consumer removed message. Quantity of retries exceeded limit';
    public const CONSUMER_ERROR = 'Email consumer error';
}
