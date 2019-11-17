<?php
declare(strict_types=1);

namespace App\Workers;

use App\Transactors\MailjetTransactor;

final class EmailWorker implements Worker
{
    private $transactor;

    /**
     * Start first transactor
     *
     * @param MailjetTransactor  $transactor
     */
    public function __construct(MailjetTransactor $transactor)
    {
        $this->transactor = $transactor;
    }

    /**
     * Send email
     *
     * @param array  $emailData
     * @return bool
     */
    public function sendEmail(array $emailData): bool
    {
        $this->transactor->preparePayload($emailData);
        return $this->transactor->send();
    }
}
