<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDto;
use Axytos\KaufAufRechnung_OXID6\ValueCalculation\ShippingCostCalculator;

class CreateInvoiceTaxGroupDtoFactory
{
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\ValueCalculation\ShippingCostCalculator
     */
    private $shippingCostCalculator;

    public function __construct(
        ShippingCostCalculator $shippingCostCalculator
    ) {
        $this->shippingCostCalculator = $shippingCostCalculator;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\OrderArticle $orderArticle
     * @return \Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDto
     */
    public function create($orderArticle)
    {
        $brutPrice = floatval($orderArticle->getFieldData("oxbrutprice"));
        $netPrice = floatval($orderArticle->getFieldData("oxnetprice"));

        $taxGroup = new CreateInvoiceTaxGroupDto();
        $taxGroup->valueToTax = $netPrice;
        $taxGroup->total = round($brutPrice - $netPrice, 2);
        $taxGroup->taxPercent = floatval($orderArticle->getFieldData("oxvat"));

        return $taxGroup;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return \Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDto
     */
    public function createShippingPosition($order)
    {
        $grossDeliveryCosts = floatval($order->getFieldData("oxdelcost"));
        $deliveryTax = floatval($order->getFieldData("oxdelvat"));

        $taxGroup = new CreateInvoiceTaxGroupDto();
        $taxGroup->valueToTax = $this->shippingCostCalculator->calculateNetPrice($grossDeliveryCosts, $deliveryTax);
        $taxGroup->total = round($grossDeliveryCosts - $taxGroup->valueToTax, 2);
        $taxGroup->taxPercent = $deliveryTax;

        return $taxGroup;
    }
}
