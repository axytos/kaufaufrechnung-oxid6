<?php

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

    /**
     * @return string
     */
    public function getPluginName()
    {
        return "KaufAufRechnung";
    }

    /**
     * @return string
     */
    public function getPluginVersion()
    {
        $packageName = 'axytos/kaufaufrechnung-oxid6';

        /** @phpstan-ignore-next-line */
        return $this->composerPackageInfoProvider->getVersion($packageName);
    }

    /**
     * @return string
     */
    public function getShopSystemName()
    {
        return "OXID-eShop";
    }

    /**
     * @return string
     */
    public function getShopSystemVersion()
    {
        return $this->shopVersionProvider->getVersion();
    }
}
