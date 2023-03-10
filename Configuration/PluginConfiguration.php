<?php

namespace Axytos\KaufAufRechnung_OXID6\Configuration;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;

class PluginConfiguration
{
    /**
     * @return string
     */
    public function getApiHost()
    {
        return $this->getSettingsValue('axytos_kaufaufrechnung_api_host');
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getSettingsValue('axytos_kaufaufrechnung_api_key');
    }

    /**
     * @return string|null
     */
    public function getClientSecret()
    {
        return $this->getSettingsValue('axytos_kaufaufrechnung_api_client_secret');
    }

    /**
     * @return string
     * @param string $settingName
     */
    private function getSettingsValue($settingName)
    {
        $settingName = (string) $settingName;
        $moduleId = 'axytos_kaufaufrechnung';

        /** @var ModuleSettingBridgeInterface */
        $moduleSettingBridge = ContainerFactory::getInstance()->getContainer()->get(ModuleSettingBridgeInterface::class);
        return $moduleSettingBridge->get($settingName, $moduleId);
    }
}
