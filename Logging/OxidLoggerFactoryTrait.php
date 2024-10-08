<?php

namespace Axytos\KaufAufRechnung_OXID6\Logging;

trait OxidLoggerFactoryTrait
{
    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function getLogger()
    {
        // see: https://docs.oxid-esales.com/developer/en/6.1/project/custom_logger_implementation.html#creating-a-custom-logger-for-a-module
        $logger = new \Monolog\Logger('axytos_kaufaufrechnung_logger');
        $logger->pushHandler(
            new \Monolog\Handler\StreamHandler(
                \OxidEsales\Eshop\Core\Registry::getConfig()->getLogsDir() . 'axytos_kaufaufrechnung.log',
                \Psr\Log\LogLevel::INFO
            )
        );

        return $logger;
    }
}
