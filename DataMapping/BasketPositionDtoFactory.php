<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\BasketPositionDto;
use Axytos\KaufAufRechnung_OXID6\ValueCalculation\ShippingCostCalculator;
use Axytos\KaufAufRechnung_OXID6\ValueCalculation\VoucherDiscountCalculator;

class BasketPositionDtoFactory
{
    /**
     * @var ShippingCostCalculator
     */
    private $shippingCostCalculator;

    /**
     * @var VoucherDiscountCalculator
     */
    private $voucherDiscountCalculator;

    public function __construct(
        ShippingCostCalculator $shippingCostCalculator,
        VoucherDiscountCalculator $voucherDiscountCalculator
    ) {
        $this->shippingCostCalculator = $shippingCostCalculator;
        $this->voucherDiscountCalculator = $voucherDiscountCalculator;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\OrderArticle $orderArticle
     *
     * @return BasketPositionDto
     */
    public function create($orderArticle)
    {
        $position = new BasketPositionDto();
        $position->productId = strval($orderArticle->getFieldData('oxartnum'));
        $position->productName = strval($orderArticle->getFieldData('oxtitle'));
        $position->quantity = floatval($orderArticle->getFieldData('oxamount'));
        $position->grossPositionTotal = floatval($orderArticle->getFieldData('oxbrutprice'));
        $position->netPositionTotal = floatval($orderArticle->getFieldData('oxnetprice'));
        $position->taxPercent = floatval($orderArticle->getFieldData('oxvat'));
        $position->netPricePerUnit = floatval($orderArticle->getFieldData('oxnprice'));
        $position->grossPricePerUnit = floatval($orderArticle->getFieldData('oxbprice'));

        return $position;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     *
     * @return BasketPositionDto
     */
    public function createShippingPosition($order)
    {
        $grossDeliveryCosts = floatval($order->getFieldData('oxdelcost'));
        $deliveryTax = floatval($order->getFieldData('oxdelvat'));

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
     * @param \OxidEsales\Eshop\Application\Model\Order                 $order
     * @param \Axytos\ECommerce\DataTransferObjects\BasketPositionDto[] $positions
     *
     * @return BasketPositionDto|null
     */
    public function createVoucherPosition($order, $positions)
    {
        $isB2B = boolval($order->getFieldData('oxisnettomode'));

        $totalVoucherDiscountForOrder = $this->voucherDiscountCalculator->calculate($order);

        if (0.0 === $totalVoucherDiscountForOrder) {
            return null;
        }

        $position = new BasketPositionDto();
        $position->productId = 'oxvoucherdiscount';
        $position->productName = 'Voucher';
        $position->quantity = 1;
        $position->taxPercent = 0;

        if ($isB2B) {
            $position->grossPositionTotal = 0;
            $position->netPositionTotal = $totalVoucherDiscountForOrder;
        } else {
            $position->grossPositionTotal = $totalVoucherDiscountForOrder;
            $position->netPositionTotal = 0;
        }

        $position->netPricePerUnit = $position->netPositionTotal;
        $position->grossPricePerUnit = $position->grossPositionTotal;

        return $position;
    }
}
