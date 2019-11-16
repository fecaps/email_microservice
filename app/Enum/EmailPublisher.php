<?php
declare(strict_types=1);

namespace App\Enum;

class EmailPublisher
{
    public const DEFAULT_RETRY          = 0;
    public const DEFAULT_ROUTING_KEY    = 'email_rk';
    public const DEFAULT_EXCHANGE       = 'email_exchange';
    public const DEFAULT_QUEUE          = 'email_queue';
}
