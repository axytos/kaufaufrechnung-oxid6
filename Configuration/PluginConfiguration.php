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
        return $this->getSettingsValue('axytos_kaufaufrechnung_client_secret');
    }

    /**
     * @return string|null
     */
    public function getCustomErrorMessage()
    {
        $errorMessage = $this->getSettingsValue('axytos_kaufaufrechnung_error_message');
        /** @phpstan-ignore-next-line */
        if (empty($errorMessage)) {
            return null;
        } else {
            return $errorMessage;
        }
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
