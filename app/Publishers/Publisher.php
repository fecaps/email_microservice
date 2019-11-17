<?php
declare(strict_types=1);

namespace App\Publishers;

interface Publisher
{
    /**
     * Publish message to the queue
     *
     * @param array $emailData
     * @param int  $retries
     * @return void
     */
    public function handle(array $emailData, int $retries): void;
}
