<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\BasketDto;
use OxidEsales\Eshop\Application\Model\Order;

class BasketDtoFactory
{
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\BasketPositionDtoCollectionFactory
     */
    private $basketPositionDtoCollectionFactory;

    public function __construct(
        BasketPositionDtoCollectionFactory $basketPositionDtoCollectionFactory
    ) {
        $this->basketPositionDtoCollectionFactory = $basketPositionDtoCollectionFactory;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     */
    public function create($order): BasketDto
    {
        $basket = new BasketDto();
        $basket->currency = strval($order->getFieldData("oxcurrency"));
        $basket->grossTotal = floatval($order->getFieldData("oxtotalbrutsum"));
        $basket->netTotal = floatval($order->getFieldData("oxtotalnetsum"));
        $basket->positions = $this->basketPositionDtoCollectionFactory->create($order);
        return $basket;
    }
}
