<?php

namespace Axytos\KaufAufRechnung_OXID6\ValueCalculation;

class TrackingIdCalculator
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     *
     * @return string[]
     */
    public function calculate($order)
    {
        $trackingCode = strval($order->getFieldData('oxtrackcode'));

        if ('' !== $trackingCode) {
            return [$trackingCode];
        }

        return [];
    }
}
