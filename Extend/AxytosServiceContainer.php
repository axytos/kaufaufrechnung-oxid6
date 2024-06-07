<?php

namespace Axytos\KaufAufRechnung_OXID6\Extend;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;

trait AxytosServiceContainer
{
    /**
     * @template T
     * @psalm-param class-string<T> $serviceName
     * @return T
     * @param string $serviceName
     */
    protected function getFromAxytosServiceContainer($serviceName)
    {
        return ContainerFactory::getInstance()
            ->getContainer()
            ->get($serviceName);
    }
}
