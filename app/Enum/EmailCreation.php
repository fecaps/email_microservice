<?php
declare(strict_types=1);

namespace App\Enum;

class EmailCreation
{
    public const START_MESSAGE  = 'Start command to create email';
    public const FROM_EMAIL     = 'What\'s the mail to send from';
    public const FROM_NAME      =  'What\'s the name to send from';
    public const TO_EMAIL       = 'What\'s the email to send to';
    public const TO_NAME        = 'What\'s the name to send to';
    public const SUBJECT        = 'What\'s the email subject';
    public const TEXT_PART      = 'What\'s the email text';
    public const HTML_PART      = 'What\'s the email html';
    public const END_MESSAGE    = 'The email created was sent to the queue';
}
