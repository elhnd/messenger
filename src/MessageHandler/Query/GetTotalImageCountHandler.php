<?php

namespace App\MessageHandler\Query;

use App\Message\Query\GetTotalImageCount;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
class GetTotalImageCountHandler
{
    public function __invoke(GetTotalImageCount $query)
    {
        // This is just a dummy implementation to show how to handle a query.
        // In a real application, you would probably fetch this data from a database.
        return 42;
    }
}