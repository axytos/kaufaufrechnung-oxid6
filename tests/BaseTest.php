<?php

namespace Axytos\KaufAufRechnung_OXID6\Tests;

use OxidEsales\Eshop\Application\Model\Address;
use OxidEsales\Eshop\Core\Base;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    /**
     * @return void
     */
    public function test_OXID6_class_can_be_autoloaded()
    {
        $address = $this->createMock(Address::class);

        $this->assertInstanceOf(Base::class, $address);
    }
}
