<?php

namespace Axytos\KaufAufRechnung_OXID6\Tests\Unit\ValueCalculation;

use Axytos\KaufAufRechnung_OXID6\ValueCalculation\TrackingIdCalculator;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class TrackingIdCalculatorTest extends TestCase
{
    /**
     * @var TrackingIdCalculator
     */
    private $sut;

    /**
     * @before
     *
     * @return void
     */
    #[Before]
    public function beforeEach()
    {
        $this->sut = new TrackingIdCalculator();
    }

    /**
     * @return void
     */
    public function test_calculate_returns_empty_as_default()
    {
        /** @var \OxidEsales\Eshop\Application\Model\Order&MockObject */
        $order = $this->createMock(\OxidEsales\Eshop\Application\Model\Order::class);

        $result = $this->sut->calculate($order);

        $this->assertTrue(is_array($result));
        $this->assertTrue([] === $result);
    }

    /**
     * @return void
     */
    public function test_calculate_returns_empty_for_empty_tracking_information()
    {
        /** @var \OxidEsales\Eshop\Application\Model\Order&MockObject */
        $order = $this->createMock(\OxidEsales\Eshop\Application\Model\Order::class);
        $order->method('getFieldData')->with('oxtrackcode')->willReturn('');

        $result = $this->sut->calculate($order);

        $this->assertTrue(is_array($result));
        $this->assertTrue([] === $result);
    }

    /**
     * @return void
     */
    public function test_calculate_array_for_non_empty_tracking_information()
    {
        /** @var \OxidEsales\Eshop\Application\Model\Order&MockObject */
        $order = $this->createMock(\OxidEsales\Eshop\Application\Model\Order::class);
        $order->method('getFieldData')->with('oxtrackcode')->willReturn('tracking code');

        $result = $this->sut->calculate($order);

        $this->assertTrue(is_array($result));
        $this->assertEquals(['tracking code'], $result);
    }
}
