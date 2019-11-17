<?php
declare(strict_types=1);

namespace App\Workers;

interface Worker
{
    /**
     * Send email
     *
     * @param array $emailData
     * @return bool
     */
    public function sendEmail(array $emailData): bool;
}
