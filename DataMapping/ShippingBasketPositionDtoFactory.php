<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDto;

class ShippingBasketPositionDtoFactory
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\OrderArticle $shippingItem
     *
     * @return ShippingBasketPositionDto
     */
    public function create($shippingItem)
    {
        $position = new ShippingBasketPositionDto();
        $position->productId = strval($shippingItem->getFieldData('oxartnum'));
        $position->quantity = floatval($shippingItem->getFieldData('oxamount'));

        return $position;
    }

    /**
     * @return ShippingBasketPositionDto
     */
    public function createShippingPosition()
    {
        $position = new ShippingBasketPositionDto();
        $position->productId = '0';
        $position->quantity = 1;

        return $position;
    }
}
