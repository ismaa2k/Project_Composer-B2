<?php

// src/Service/CustomLogger.php

namespace App\Service;

use Psr\Log\LoggerInterface;

class CustomLogger
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logError(string $message)
    {
        $this->logger->error($message);
    }
}
