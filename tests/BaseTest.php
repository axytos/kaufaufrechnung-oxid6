<?php

namespace Axytos\KaufAufRechnung_OXID6\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class BaseTest extends TestCase
{
    /**
     * @return void
     */
    public function test_oxi_d_class_can_be_autoloaded()
    {
        $address = $this->createMock(\OxidEsales\Eshop\Application\Model\Address::class);

        $this->assertInstanceOf(\OxidEsales\Eshop\Core\Base::class, $address);
    }
}
