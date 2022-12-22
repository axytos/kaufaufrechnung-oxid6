<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDto;
use OxidEsales\Eshop\Application\Model\OrderArticle;

class ShippingBasketPositionDtoFactory
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\OrderArticle $shippingItem
     */
    public function create($shippingItem): ShippingBasketPositionDto
    {
        $position = new ShippingBasketPositionDto();
        $position->productId = strval($shippingItem->getFieldData("oxartnum"));
        $position->quantity = intval($shippingItem->getFieldData("oxamount"));
        return $position;
    }

    public function createShippingPosition(): ShippingBasketPositionDto
    {
        $position = new ShippingBasketPositionDto();
        $position->productId = '0';
        $position->quantity = 1;
        return $position;
    }
}
