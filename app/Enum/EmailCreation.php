<?php
declare(strict_types=1);

namespace App\Enum;

final class EmailCreation
{
    public const START_MESSAGE      = 'Start command to create email';
    public const FROM_EMAIL_MESSAGE = 'What\'s the mail to send from';
    public const FROM_NAME_MESSAGE  =  'What\'s the name to send from';
    public const TO_EMAIL_MESSAGE   = 'What\'s the email to send to';
    public const TO_NAME_MESSAGE    = 'What\'s the name to send to';
    public const SUBJECT_MESSAGE    = 'What\'s the email subject';
    public const TEXT_PART_MESSAGE  = 'What\'s the email text';
    public const HTML_PART_MESSAGE  = 'What\'s the email html';
    public const END_MESSAGE        = 'The email created was sent to the queue';
    public const INVALID_MESSAGE    = 'Email couldn\'t be created. See error messages below:';
    public const FROM_EMAIL_KEY     = 'fromEmail';
    public const FROM_NAME_KEY      = 'fromName';
    public const TO_EMAIL_KEY       = 'toEmail';
    public const TO_NAME_KEY        = 'toName';
    public const SUBJECT_KEY        = 'subject';
    public const TEXT_PART_KEY      = 'textPart';
    public const HTML_PART_KEY      = 'htmlPart';
}
