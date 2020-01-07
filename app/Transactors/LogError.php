<?php
declare(strict_types=1);

namespace App\Transactors;

trait LogError
{
    protected function logError(string $log, string $message): void
    {
        $logMessage = sprintf($log, $message);
        \Log::channel('consumer')->info($logMessage);
    }
}
