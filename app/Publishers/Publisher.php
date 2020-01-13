<?php
declare(strict_types=1);

namespace App\Publishers;

use App\DTO\Email;

interface Publisher
{
    /**
     * Publish message to the queue
     *
     * @param Email $emailDTO
     * @param int  $retries
     * @return void
     */
    public function handle(Email $emailDTO, int $retries = 0): void;
}
