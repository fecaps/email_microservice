<?php
declare(strict_types=1);

namespace App\Transactors;

abstract class Transactor
{
    abstract public function preparePayload(array $inputData);

    abstract public function send(): bool;
}
