<?php

namespace Axytos\KaufAufRechnung_OXID6\Extend;

use Axytos\KaufAufRechnung_OXID6\DependencyInjection\ContainerFactory;

trait AxytosServiceContainer
{
    /**
     * @template T
     *
     * @param string $serviceName
     *
     * @psalm-param class-string<T> $serviceName
     *
     * @return T
     */
    protected function getFromAxytosServiceContainer($serviceName)
    {
        return ContainerFactory::getInstance()
            ->getContainer()
            ->get($serviceName)
        ;
    }
}
