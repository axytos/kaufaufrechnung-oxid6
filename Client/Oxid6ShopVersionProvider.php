<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\Client;

use OxidEsales\Eshop\Core\ShopVersion;

class Oxid6ShopVersionProvider
{
    public function getVersion(): string
    {
        return ShopVersion::getVersion();
    }
}
