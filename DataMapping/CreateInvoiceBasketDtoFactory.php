<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto;
use Axytos\KaufAufRechnung_OXID6\ValueCalculation\ShippingCostCalculator;

class CreateInvoiceBasketDtoFactory
{
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\CreateInvoiceBasketPositionDtoCollectionFactory
     */
    private $createInvoiceBasketPositionDtoCollectionFactory;

    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\CreateInvoiceTaxGroupDtoCollectionFactory
     */
    private $createInvoiceTaxGroupDtoCollectionFactory;

    /**
     * @var \Axytos\KaufAufRechnung_OXID6\ValueCalculation\ShippingCostCalculator
     */
    private $shippingCostCalculator;

    public function __construct(
        CreateInvoiceBasketPositionDtoCollectionFactory $createInvoiceBasketPositionDtoCollectionFactory,
        CreateInvoiceTaxGroupDtoCollectionFactory $createInvoiceTaxGroupDtoCollectionFactory,
        ShippingCostCalculator $shippingCostCalculator
    ) {
        $this->createInvoiceBasketPositionDtoCollectionFactory = $createInvoiceBasketPositionDtoCollectionFactory;
        $this->createInvoiceTaxGroupDtoCollectionFactory = $createInvoiceTaxGroupDtoCollectionFactory;
        $this->shippingCostCalculator = $shippingCostCalculator;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return \Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto
     */
    public function create($order)
    {
        $grossDeliveryCosts = floatval($order->getFieldData("oxdelcost"));
        $deliveryTax = floatval($order->getFieldData("oxdelvat"));
        $netDeliveryCosts = $this->shippingCostCalculator->calculateNetPrice($grossDeliveryCosts, $deliveryTax);
        $voucherDiscount = floatval($order->getFieldData("oxvoucherdiscount"));

        $basket = new CreateInvoiceBasketDto();
        $basket->positions = $this->createInvoiceBasketPositionDtoCollectionFactory->create($order);
        $basket->taxGroups = $this->createInvoiceTaxGroupDtoCollectionFactory->create($order);
        $basket->grossTotal = floatval($order->getFieldData("oxtotalbrutsum")) + $grossDeliveryCosts - $voucherDiscount;
        $basket->netTotal = floatval($order->getFieldData("oxtotalnetsum")) + $netDeliveryCosts;
        return $basket;
    }
}
