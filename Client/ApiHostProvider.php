<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\Client;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;
use Axytos\KaufAufRechnung_OXID6\Configuration\PluginConfiguration;

class ApiHostProvider implements ApiHostProviderInterface
{
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\Configuration\PluginConfiguration
     */
    public $pluginConfig;

    public function __construct(PluginConfiguration $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    public function getApiHost(): string
    {
        return $this->pluginConfig->getApiHost();
    }
}
