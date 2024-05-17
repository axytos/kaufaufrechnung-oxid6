<?php

namespace Axytos\KaufAufRechnung_OXID6\Logging;

use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use OxidEsales\Eshop\Core\Registry;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LoggerAdapter implements LoggerAdapterInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct()
    {
        $this->logger = self::getLogger();
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

    /**
     * @return \Psr\Log\LoggerInterface
     */
    private static function getLogger()
    {
        // see: https://docs.oxid-esales.com/developer/en/6.1/project/custom_logger_implementation.html#creating-a-custom-logger-for-a-module
        $logger = new Logger('axytos_kaufaufrechnung_logger');
        $logger->pushHandler(
            new StreamHandler(Registry::getConfig()->getLogsDir() . 'axytos_kaufaufrechnung.log', LogLevel::INFO)
        );

        return $logger;
    }
}
