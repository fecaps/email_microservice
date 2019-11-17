<?php
declare(strict_types=1);

namespace App\Transactors;

abstract class Transactor
{
    /**
     * Prepare email payload
     *
     * @param array $inputData
     */
    abstract public function preparePayload(array $inputData);

    /**
     * Send email
     *
     */
    abstract public function send(): bool;

    /**
     * Send trigger email
     *
     */
    abstract public function sendTrigger(): bool;
}
