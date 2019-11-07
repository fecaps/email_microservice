<?php
declare(strict_types=1);

namespace App\Transactors;

interface Transactor
{
    public function preparePayload(array $inputData);

    public function send(): bool;
}
