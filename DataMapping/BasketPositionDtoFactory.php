<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\BasketPositionDto;
use Axytos\KaufAufRechnung_OXID6\ValueCalculation\ShippingCostCalculator;

class BasketPositionDtoFactory
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
        $position->netPositionTotal = $this->shippingCostCalculator->calculateNetPrice($grossDeliveryCosts, $deliveryTax);
        $position->taxPercent = $deliveryTax;
        $position->netPricePerUnit = $position->netPositionTotal;
        $position->grossPricePerUnit = $position->grossPositionTotal;
        return $position;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @param \Axytos\ECommerce\DataTransferObjects\BasketPositionDto[] $positions
     * @return \Axytos\ECommerce\DataTransferObjects\BasketPositionDto|null
     */
    public function createVoucherPosition($order, $positions)
    {
        $voucherDiscountGross = -floatval($order->getFieldData("oxvoucherdiscount"));
        if ($voucherDiscountGross === 0.0) {
            return null;
        }

        $netPosSum = array_sum(array_map(
            function (BasketPositionDto $dto) {
                return $dto->netPositionTotal;
            },
            $positions
        ));
        $voucherDiscountNet = round(floatval($order->getFieldData("oxtotalnetsum")) - $netPosSum, 2);

        $voucherTaxPercent = round((($voucherDiscountGross / $voucherDiscountNet) - 1) * 100);

        $position = new BasketPositionDto();
        $position->productId = 'oxvoucherdiscount';
        $position->productName = 'Voucher';
        $position->quantity = 1;
        $position->grossPositionTotal = $voucherDiscountGross;
        $position->netPositionTotal = $voucherDiscountNet;
        $position->taxPercent = $voucherTaxPercent;
        $position->netPricePerUnit = $position->netPositionTotal;
        $position->grossPricePerUnit = $position->grossPositionTotal;
        return $position;
    }
}
