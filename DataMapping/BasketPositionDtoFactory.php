<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\BasketPositionDto;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\OrderArticle;

class BasketPositionDtoFactory
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\OrderArticle $orderArticle
     * @return \Axytos\ECommerce\DataTransferObjects\BasketPositionDto
     */
    public function create($orderArticle)
    {
        $position = new BasketPositionDto();
        $position->productId = strval($orderArticle->getFieldData("oxartnum"));
        $position->productName = strval($orderArticle->getFieldData("oxtitle"));
        $position->quantity = intval($orderArticle->getFieldData("oxamount"));
        $position->grossPositionTotal = floatval($orderArticle->getFieldData("oxbrutprice"));
        $position->netPositionTotal = floatval($orderArticle->getFieldData("oxnetprice"));
        $position->taxPercent = floatval($orderArticle->getFieldData("oxvat"));
        $position->netPricePerUnit = floatval($orderArticle->getFieldData("oxnprice"));
        $position->grossPricePerUnit = floatval($orderArticle->getFieldData("oxbprice"));

        return $position;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return \Axytos\ECommerce\DataTransferObjects\BasketPositionDto
     */
    public function createShippingPosition($order)
    {
        $grossDeliveryCosts = floatval($order->getFieldData("oxdelcost"));
        $deliveryTax = floatval($order->getFieldData("oxdelvat"));

        $position = new BasketPositionDto();
        $position->productId = '0';
        $position->productName = 'Shipping';
        $position->quantity = 1;
        $position->grossPositionTotal = $grossDeliveryCosts;
        $position->netPositionTotal = round($grossDeliveryCosts * (1 - $deliveryTax / 100), 2);
        $position->taxPercent = $deliveryTax;
        $position->netPricePerUnit = $position->netPositionTotal;
        $position->grossPricePerUnit = $position->grossPositionTotal;
        return $position;
    }
}
