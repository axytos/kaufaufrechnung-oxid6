<?php

namespace Axytos\KaufAufRechnung_OXID6\Tests\Unit\Extend\Model;

use Axytos\KaufAufRechnung_OXID6\Extend\Model\AxytosPayment;
use Axytos\KaufAufRechnung_OXID6\Extend\Model\AxytosPaymentGateway;
use Axytos\KaufAufRechnung_OXID6\Extend\Model\AxytosPaymentList;
use PHPUnit\Framework\TestCase;

/**
 * This test validates that OXID extension models do not break the module inheritance chain.
 * That is, OXID will dynamically generate the following inhertance chain over all 3rd party modules:
 *
 *    Module1\Model < Module2\Model < ... < OXID\ModelBase
 *
 * see: \OxidEsales\EshopCommunity\Core\Module\ModuleChainsGenerator::createClassExtension($parentClass, $moduleClass)
 *
 * So we must ensure that signatures of overridden methods are type invariant.
 * When this test fails it will raise a fatal error.
 *
 * @internal
 */
class ModuleChainsTest extends TestCase
{
    /**
     * @return void
     */
    public function test_axytos_payment_does_not_break_module_chain()
    {
        class_alias(AxytosPayment::class, 'OtherPayment_parent');

        $classDeclaration = '
        class OtherPayment extends OtherPayment_parent
        {
            public function setPaymentVatOnTop($blOnTop) {}
            public function getGroups() {}
            public function setDynValues($aDynValues) {}
            public function setDynValue($oKey, $oVal) {}
            public function getDynValues() {}
            public function getPaymentValue($dBasePrice) {}
            public function getBaseBasketPriceForPaymentCostCalc($oBasket) {}
            public function calculate($oBasket) {}
            public function getPrice() {}
            public function getFNettoPrice() {}
            public function getFBruttoPrice() {}
            public function getFPriceVat() {}
            public function getCountries() {}
            public function delete($sOxId = null) {}
            public function isValidPayment($aDynValue, $sShopId, $oUser, $dBasketPrice, $sShipSetId) {}
            public function getPaymentErrorNumber() {}
        }
        ';

        eval($classDeclaration);
        $this->assertTrue(class_exists('OtherPayment'));
    }

    /**
     * @return void
     */
    public function test_axytos_payment_gateway_does_not_break_module_chain()
    {
        class_alias(AxytosPaymentGateway::class, 'OtherPaymentGateway_parent');

        $classDeclaration = '
        class OtherPaymentGateway extends OtherPaymentGateway_parent
        {
            public function setPaymentParams($oUserpayment) {}
            public function executePayment($amount, &$oOrder) {}
            public function getLastErrorNo() {}
            public function getLastError() {}
            protected function _isActive() {}
        }
        ';

        eval($classDeclaration);
        $this->assertTrue(class_exists('OtherPaymentGateway'));
    }

    /**
     * @return void
     */
    public function test_axytos_payment_list_does_not_break_module_chain()
    {
        class_alias(AxytosPaymentList::class, 'OtherPaymentList_parent');

        $classDeclaration = '
        class OtherPaymentList extends OtherPaymentList_parent
        {
            public function setHomeCountry($sHomeCountry) {}
            protected function _getFilterSelect($sShipSetId, $dPrice, $oUser) {}
            public function getCountryId($oUser) {}
            public function getPaymentList($sShipSetId, $dPrice, $oUser = null) {}
            public function loadNonRDFaPaymentList() {}
            public function loadRDFaPaymentList($dPrice = null) {}
        }
        ';

        eval($classDeclaration);
        $this->assertTrue(class_exists('OtherPaymentList'));
    }
}
