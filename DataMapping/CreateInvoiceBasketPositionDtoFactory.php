<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketPositionDto;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\OrderArticle;

class CreateInvoiceBasketPositionDtoFactory
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\OrderArticle $orderArticle
     */
    public function create($orderArticle): CreateInvoiceBasketPositionDto
    {
        $position = new CreateInvoiceBasketPositionDto();
        $position->productId = strval($orderArticle->getFieldData("oxartnum"));
        $position->productName = strval($orderArticle->getFieldData("oxtitle"));
        $position->quantity = intval($orderArticle->getFieldData("oxamount"));
        $position->taxPercent = floatval($orderArticle->getFieldData("oxvat"));
        $position->netPricePerUnit = floatval($orderArticle->getFieldData("oxnprice"));
        $position->grossPricePerUnit = floatval($orderArticle->getFieldData("oxbprice"));
        $position->netPositionTotal = floatval($orderArticle->getFieldData("oxnetprice"));
        $position->grossPositionTotal = floatval($orderArticle->getFieldData("oxbrutprice"));

        return $position;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     */
    public function createShippingPosition($order): CreateInvoiceBasketPositionDto
    {
        $position = new CreateInvoiceBasketPositionDto();
        $position->productId = '0';
        $position->productName = 'Shipping';
        $position->quantity = 1;
        $position->taxPercent = floatval($order->getFieldData("oxdelvat"));
        $position->netPricePerUnit = round(floatval($order->getFieldData("oxdelcost")) * floatval($order->getFieldData("oxdelvat")) / 100, 2);
        $position->grossPricePerUnit = floatval($order->getFieldData("oxdelcost"));
        $position->netPositionTotal = round(floatval($order->getFieldData("oxdelcost")) * floatval($order->getFieldData("oxdelvat")) / 100, 2);
        $position->grossPositionTotal = floatval($order->getFieldData("oxdelcost"));
        return $position;
    }
}
