<?php

namespace Axytos\KaufAufRechnung_OXID6\Client;

use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;
use Axytos\KaufAufRechnung_OXID6\Configuration\PluginConfiguration;

class ApiKeyProvider implements ApiKeyProviderInterface
{
    /**
     * @var PluginConfiguration
     */
    public $pluginConfig;

    public function __construct(PluginConfiguration $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->pluginConfig->getApiKey();
    }
}
