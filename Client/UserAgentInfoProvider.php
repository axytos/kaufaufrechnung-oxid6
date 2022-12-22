<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\Client;

use Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface;
use Axytos\ECommerce\PackageInfo\ComposerPackageInfoProvider;

class UserAgentInfoProvider implements UserAgentInfoProviderInterface
{
    /**
     * @var \Axytos\ECommerce\PackageInfo\ComposerPackageInfoProvider
     */
    private $composerPackageInfoProvider;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\Client\Oxid6ShopVersionProvider
     */
    private $shopVersionProvider;

    public function __construct(ComposerPackageInfoProvider $composerPackageInfoProvider, Oxid6ShopVersionProvider $shopVersionProvider)
    {
        $this->composerPackageInfoProvider = $composerPackageInfoProvider;
        $this->shopVersionProvider = $shopVersionProvider;
    }

    public function getPluginName(): string
    {
        return "KaufAufRechnung";
    }

    public function getPluginVersion(): string
    {
        $packageName = 'axytos/kaufaufrechnung-oxid6';

        /** @phpstan-ignore-next-line */
        return $this->composerPackageInfoProvider->getVersion($packageName);
    }

    public function getShopSystemName(): string
    {
        return "OXID-eShop";
    }

    public function getShopSystemVersion(): string
    {
        return $this->shopVersionProvider->getVersion();
    }
}
