<?php

namespace Axytos\KaufAufRechnung_OXID6\Logging;

use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use OxidEsales\Eshop\Core\Registry;
use Psr\Log\LoggerInterface;

class LoggerAdapter implements LoggerAdapterInterface
{
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = Registry::getLogger();
    }

    public function error(string $message): void
    {
        $this->logger->error($message);
    }

    public function warning(string $message): void
    {
        $this->logger->warning($message);
    }

    public function info(string $message): void
    {
        $this->logger->info($message);
    }

    public function debug(string $message): void
    {
        $this->logger->debug($message);
    }
}
