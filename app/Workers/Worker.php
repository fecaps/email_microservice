<?php
declare(strict_types=1);

namespace App\Workers;

use App\DTO\Email;

interface Worker
{
    /**
     * Send email
     *
     * @param Email  $email
     * @return bool
     */
    public function sendEmail(Email $email): bool;
}
