<?php

namespace Axytos\KaufAufRechnung_OXID6\Configuration;

trait OxidSettingsAccessTrait
{
    /**
     * @param string $settingName
     *
     * @return string
     */
    protected function getSettingsValue($settingName)
    {
        $settingName = (string) $settingName;
        $moduleId = 'axytos_kaufaufrechnung';

        /** @var \OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface */
        $moduleSettingBridge = \Axytos\KaufAufRechnung_OXID6\DependencyInjection\ContainerFactory::getInstance()
            ->getContainer()
            ->get(\OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface::class)
        ;

        return $moduleSettingBridge->get($settingName, $moduleId);
    }
}
