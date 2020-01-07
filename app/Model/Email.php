<?php
declare(strict_types=1);

namespace App\Model;

interface Email
{
    public function storeEmail(array $email): array;
}
