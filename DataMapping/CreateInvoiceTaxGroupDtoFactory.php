<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDto;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\OrderArticle;

class CreateInvoiceTaxGroupDtoFactory
{
    public function create(OrderArticle $orderArticle): CreateInvoiceTaxGroupDto
    {
        $taxGroup = new CreateInvoiceTaxGroupDto();
        $taxGroup->total = floatval($orderArticle->getFieldData("oxbrutprice"));
        $taxGroup->valueToTax = floatval($orderArticle->getFieldData("oxnetprice"));
        $taxGroup->taxPercent = floatval($orderArticle->getFieldData("oxvat"));

        return $taxGroup;
    }

    public function createShippingPosition(Order $order): CreateInvoiceTaxGroupDto
    {
        $taxGroup = new CreateInvoiceTaxGroupDto();
        $taxGroup->total = floatval($order->getFieldData("oxdelcost"));
        $taxGroup->valueToTax = round(floatval($order->getFieldData("oxdelcost")) * (1 - floatval($order->getFieldData("oxdelvat")) / 100), 2);
        $taxGroup->taxPercent = floatval($order->getFieldData("oxdelvat"));

        return $taxGroup;
    }
}
