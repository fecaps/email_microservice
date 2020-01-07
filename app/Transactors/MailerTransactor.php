<?php
declare(strict_types=1);

namespace App\Transactors;

use App\DTO\Email;

interface MailerTransactor
{
    /**
     * Send email
     *
     * @param Email  $email
     * @return bool
     */
    public function send(Email $email): bool;
}
