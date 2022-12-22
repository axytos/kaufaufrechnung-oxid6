<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\ValueCalculation;

use OxidEsales\Eshop\Application\Model\Order;

class TrackingIdCalculator
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     */
    public function calculate($order): array
    {
        /** @var string */
        $trackingCode = $order->getFieldData("oxtrackcode");

        if ($trackingCode != "") {
            return [$trackingCode];
        }

        return [];
    }
}
