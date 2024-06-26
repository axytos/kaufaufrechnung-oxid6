<?php

namespace Axytos\KaufAufRechnung_OXID6\Tests\Unit\DataMapping;

use Axytos\KaufAufRechnung_OXID6\DataMapping\CreateInvoiceBasketPositionDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\BasketPositionDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\CreateInvoiceTaxGroupDtoFactory;
use Axytos\KaufAufRechnung_OXID6\ValueCalculation\ShippingCostCalculator;
use Axytos\KaufAufRechnung_OXID6\ValueCalculation\VoucherDiscountCalculator;
use OxidEsales\Eshop\Application\Model\Order;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BasketPositionDtoMappingForZeroValueVouchersTest extends TestCase
{
    /**
     * @param bool $oxisnettomode
     * @param mixed $oxvoucherdiscount
     * @return void
     * @dataProvider getZeroValues
     */
    #[DataProvider('getZeroValues')]
    public function test_BasketPositionDtoFactory($oxisnettomode, $oxvoucherdiscount)
    {
        $sut = new BasketPositionDtoFactory(new ShippingCostCalculator(), new VoucherDiscountCalculator());
        $order = $this->createOrderMock($oxisnettomode, $oxvoucherdiscount);
        $result = $sut->createVoucherPosition($order, []);
        $this->assertNull($result);
    }

    /**
     * @param bool $oxisnettomode
     * @param mixed $oxvoucherdiscount
     * @return void
     * @dataProvider getZeroValues
     */
    #[DataProvider('getZeroValues')]
    public function test_CreateInvoiceBasketPositionDtoFactory($oxisnettomode, $oxvoucherdiscount)
    {
        $sut = new CreateInvoiceBasketPositionDtoFactory(new ShippingCostCalculator(), new VoucherDiscountCalculator());
        $order = $this->createOrderMock($oxisnettomode, $oxvoucherdiscount);
        $result = $sut->createVoucherPosition($order, []);
        $this->assertNull($result);
    }

    /**
     * @param bool $oxisnettomode
     * @param mixed $oxvoucherdiscount
     * @return void
     * @dataProvider getZeroValues
     */
    #[DataProvider('getZeroValues')]
    public function test_CreateInvoiceTaxGroupDtoFactory($oxisnettomode, $oxvoucherdiscount)
    {
        $sut = new CreateInvoiceTaxGroupDtoFactory(new ShippingCostCalculator(), new VoucherDiscountCalculator());
        $order = $this->createOrderMock($oxisnettomode, $oxvoucherdiscount);
        $result = $sut->createVoucherPosition($order);
        $this->assertNull($result);
    }

    /**
     * @param mixed $oxisnettomode
     * @param mixed $oxvoucherdiscount
     * @return Order&MockObject
     */
    private function createOrderMock($oxisnettomode, $oxvoucherdiscount)
    {
        /** @var Order&MockObject */
        $order = $this->createMock(Order::class);
        $order->method('getFieldData')->willReturnCallback(function ($field) use ($oxisnettomode, $oxvoucherdiscount) {
            switch ($field) {
                case 'oxisnettomode':
                    return $oxisnettomode;
                case 'oxvoucherdiscount':
                    return $oxvoucherdiscount;
            }
            return null;
        });

        return $order;
    }

    /**
     * @return array<array<mixed>>
     */
    public static function getZeroValues()
    {
        // [oxisnettomode, oxvoucherdiscount]
        return [
            [true, intval(0)],
            [true, floatval(0)],
            [true, ''],
            [true, false],
            [true, null],

            [false, intval(0)],
            [false, floatval(0)],
            [false, ''],
            [false, false],
            [false, null],
        ];
    }
}
