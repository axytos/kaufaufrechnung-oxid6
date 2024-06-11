<?php

namespace Axytos\KaufAufRechnung_OXID6\ValueCalculation;

use OxidEsales\Eshop\Application\Model\Order;

class VoucherDiscountCalculator
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return float
     */
    public function calculate($order)
    {
        // the total monetary value of all applied vouchers
        $voucherDiscountForOrder = floatval($order->getFieldData("oxvoucherdiscount"));

        if ($voucherDiscountForOrder === 0.0) {
            return 0.0;
        }

        return -1 * $voucherDiscountForOrder;
    }
}
