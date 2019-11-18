<?php
declare(strict_types=1);

namespace App\Enum;

final class LogMessages
{
    public const START_WEB              = 'New message requested - Web';
    public const MAILJET_SEND_ERROR     = 'Mailjet send error: %s';
    public const SENDGRID_PAYLOAD_ERROR = 'Sendgrid payload error: %s';
    public const SENDGRID_SEND_ERROR    = 'Sendgrid send error: %s';
    public const MESSAGE_PUBLISHED      = 'Message published to queue. Message: %s - Retries: %s';
    public const START_CONSOLE          = 'New message requested - Console';
    public const MESSAGE_REMOVED        = 'Message removed from the queue. Message: %s';
    public const MESSAGE_RESOLVED       = 'Message resolved. Message: %s';
}
