<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\BasketDto;
use Axytos\KaufAufRechnung_OXID6\ValueCalculation\ShippingCostCalculator;

class BasketDtoFactory
{
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\BasketPositionDtoCollectionFactory
     */
    private $basketPositionDtoCollectionFactory;

    /**
     * @var \Axytos\KaufAufRechnung_OXID6\ValueCalculation\ShippingCostCalculator
     */
    private $shippingCostCalculator;

    public function __construct(
        BasketPositionDtoCollectionFactory $basketPositionDtoCollectionFactory,
        ShippingCostCalculator $shippingCostCalculator
    ) {
        $this->basketPositionDtoCollectionFactory = $basketPositionDtoCollectionFactory;
        $this->shippingCostCalculator = $shippingCostCalculator;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return \Axytos\ECommerce\DataTransferObjects\BasketDto
     */
    public function create($order)
    {
        $grossDeliveryCosts = floatval($order->getFieldData("oxdelcost"));
        $deliveryTax = floatval($order->getFieldData("oxdelvat"));
        $netDeliveryCosts = $this->shippingCostCalculator->calculateNetPrice($grossDeliveryCosts, $deliveryTax);
        $voucherDiscount = floatval($order->getFieldData("oxvoucherdiscount"));

        $basket = new BasketDto();
        $basket->currency = strval($order->getFieldData("oxcurrency"));
        $basket->grossTotal = floatval($order->getFieldData("oxtotalbrutsum")) + $grossDeliveryCosts - $voucherDiscount;
        $basket->netTotal = floatval($order->getFieldData("oxtotalnetsum")) + $netDeliveryCosts;
        $basket->positions = $this->basketPositionDtoCollectionFactory->create($order);
        return $basket;
    }
}
