<?php

namespace Axytos\KaufAufRechnung_OXID6\Client;

use OxidEsales\Eshop\Core\ShopVersion;

trait OxidShopVersionAccessTrait
{
    /**
     * @return string
     */
    protected function getVersion()
    {
        return ShopVersion::getVersion();
    }
}
