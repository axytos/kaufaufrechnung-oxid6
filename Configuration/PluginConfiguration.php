<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\Configuration;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;

class PluginConfiguration
{
    public function getApiHost(): string
    {
        return $this->getSettingsValue('axytos_kaufaufrechnung_api_host');
    }

    public function getApiKey(): string
    {
        return $this->getSettingsValue('axytos_kaufaufrechnung_api_key');
    }

    public function getClientSecret(): ?string
    {
        return $this->getSettingsValue('axytos_kaufaufrechnung_api_client_secret');
    }

    /**
     * @return string
     */
    private function getSettingsValue(string $settingName)
    {
        $moduleId = 'axytos_kaufaufrechnung';

        /** @var ModuleSettingBridgeInterface */
        $moduleSettingBridge = ContainerFactory::getInstance()->getContainer()->get(ModuleSettingBridgeInterface::class);
        return $moduleSettingBridge->get($settingName, $moduleId);
    }
}
