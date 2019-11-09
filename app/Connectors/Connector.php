<?php
declare(strict_types=1);

namespace App\Connectors;

interface Connector
{
    public function getClient();
}
