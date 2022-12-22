<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\Client;

use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;
use Axytos\KaufAufRechnung_OXID6\Configuration\PluginConfiguration;

class ApiKeyProvider implements ApiKeyProviderInterface
{
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\Configuration\PluginConfiguration
     */
    public $pluginConfig;

    public function __construct(PluginConfiguration $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    public function getApiKey(): string
    {
        return $this->pluginConfig->getApiKey();
    }
}
