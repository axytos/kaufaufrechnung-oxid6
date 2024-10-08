<?php

namespace Axytos\KaufAufRechnung_OXID6\DependencyInjection;

class ContainerFactory
{
    /**
     * @return \OxidEsales\EshopCommunity\Internal\Container\ContainerFactory
     */
    public static function getInstance()
    {
        return \OxidEsales\EshopCommunity\Internal\Container\ContainerFactory::getInstance();
    }
}
