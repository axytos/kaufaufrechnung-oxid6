<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\BasketDto;
use OxidEsales\Eshop\Application\Model\Order;

class BasketDtoFactory
{
    private BasketPositionDtoCollectionFactory $basketPositionDtoCollectionFactory;

    public function __construct(
        BasketPositionDtoCollectionFactory $basketPositionDtoCollectionFactory
    ) {
        $this->basketPositionDtoCollectionFactory = $basketPositionDtoCollectionFactory;
    }

    public function create(Order $order): BasketDto
    {
        $basket = new BasketDto();
        $basket->currency = strval($order->getFieldData("oxcurrency"));
        $basket->grossTotal = floatval($order->getFieldData("oxtotalbrutsum"));
        $basket->netTotal = floatval($order->getFieldData("oxtotalnetsum"));
        $basket->positions = $this->basketPositionDtoCollectionFactory->create($order);
        return $basket;
    }
}
