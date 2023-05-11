<?php

namespace Axytos\KaufAufRechnung_OXID6\ValueCalculation;

use OxidEsales\Eshop\Application\Model\Order;

class TrackingIdCalculator
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return string[]
     */
    public function calculate($order)
    {
        $trackingCode = $order->getFieldData("oxtrackcode");

        if (is_string($trackingCode) && $trackingCode !== "") {
            return [$trackingCode];
        }

        return [];
    }
}
