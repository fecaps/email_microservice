<?php
declare(strict_types=1);

namespace App\Workers;

use App\Transactors\MailjetTransactor;

final class EmailWorker
{
    private $transactor;

    public function __construct(MailjetTransactor $transactor)
    {
        $this->transactor = $transactor;
    }

    public function sendEmail(array $emailData): bool
    {
        $this->transactor->preparePayload($emailData);
        return $this->transactor->send();
    }
}
