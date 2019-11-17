<?php
declare(strict_types=1);

namespace App\Connectors;

interface Connector
{
    /**
     * Get client connection.
     *
     */
    public function getClient();
}
