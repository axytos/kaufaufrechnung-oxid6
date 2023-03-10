<?php

namespace Axytos\KaufAufRechnung_OXID6\Client;

use OxidEsales\Eshop\Core\ShopVersion;

class Oxid6ShopVersionProvider
{
    /**
     * @return string
     */
    public function getVersion()
    {
        return ShopVersion::getVersion();
    }
}
