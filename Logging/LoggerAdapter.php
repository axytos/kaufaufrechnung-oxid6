<?php

namespace Axytos\KaufAufRechnung_OXID6\Logging;

use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use OxidEsales\Eshop\Core\Registry;
use Psr\Log\LoggerInterface;

class LoggerAdapter implements LoggerAdapterInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct()
    {
        $this->logger = Registry::getLogger();
    }

    /**
     * @param string $message
     * @return void
     */
    public function error($message)
    {
        $this->logger->error($message);
    }

    /**
     * @param string $message
     * @return void
     */
    public function warning($message)
    {
        $this->logger->warning($message);
    }

    /**
     * @param string $message
     * @return void
     */
    public function info($message)
    {
        $this->logger->info($message);
    }

    /**
     * @param string $message
     * @return void
     */
    public function debug($message)
    {
        $this->logger->debug($message);
    }
}
