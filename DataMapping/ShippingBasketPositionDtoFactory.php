<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDto;
use OxidEsales\Eshop\Application\Model\OrderArticle;

class ShippingBasketPositionDtoFactory
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\OrderArticle $shippingItem
     * @return \Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDto
     */
    public function create($shippingItem)
    {
        $position = new ShippingBasketPositionDto();
        $position->productId = strval($shippingItem->getFieldData("oxartnum"));
        $position->quantity = intval($shippingItem->getFieldData("oxamount"));
        return $position;
    }

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDto
     */
    public function createShippingPosition()
    {
        $position = new ShippingBasketPositionDto();
        $position->productId = '0';
        $position->quantity = 1;
        return $position;
    }
}
