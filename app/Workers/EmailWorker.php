<?php
declare(strict_types=1);

namespace App\Workers;

use App\DTO\Email;

final class EmailWorker implements Worker
{
    /** @var ​ MailerTransactor[] */
    private $mailers;

    /**
     * Start mailers transactors
     *
     * @param array  $mailers
     */
    public function __construct(array $mailers)
    {
        $this->mailers = $mailers;
    }

    /**
     * Send email
     *
     * @param Email  $email
     * @return bool
     */
    public function sendEmail(Email $email): bool
    {
        foreach ($this->mailers as $mailer) {
            if ($mailer->send($email)) {
                return true;
            }
        }

        return false;
    }
}
