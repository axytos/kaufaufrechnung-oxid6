<?php

namespace Axytos\KaufAufRechnung_OXID6\Events;

use Axytos\KaufAufRechnung_OXID6\DataAbstractionLayer\OrderRepository;
use Axytos\KaufAufRechnung_OXID6\DependencyInjection\ContainerFactory;
use Axytos\KaufAufRechnung_OXID6\ErrorReporting\ErrorHandler;

class AxytosEvents
{
    const PAYMENT_METHOD_ID = 'axytos_kaufaufrechnung';
    const PAYMENT_METHOD_DE_DESC = 'Kauf auf Rechnung';
    const PAYMENT_METHOD_DE_LONG_DESC = 'Sie zahlen bequem die Rechnung, sobald Sie die Ware erhalten haben, innerhalb der Zahlfrist';
    const PAYMENT_METHOD_EN_DESC = 'Buy Now Pay Later';
    const PAYMENT_METHOD_EN_LONG_DESC = 'You conveniently pay the invoice as soon as you receive the goods, within the payment period';
    const PAYMENT_METHOD_FR_DESC = 'Buy Now Pay Later';
    const PAYMENT_METHOD_FR_LONG_DESC = 'Vous payez la facture dès que vous recevez la marchandise, dans le délai de paiement.';
    const PAYMENT_METHOD_NL_DESC = 'Buy Now Pay Later';
    const PAYMENT_METHOD_NL_LONG_DESC = 'Je moet de factuur betalen zodra je de goederen hebt ontvangen, binnen de betalingstermijn.';
    const PAYMENT_METHOD_ES_DESC = 'Buy Now Pay Later';
    const PAYMENT_METHOD_ES_LONG_DESC = 'Pagas la factura convenientemente en cuanto has recibido la mercancía, dentro del plazo de pago.';

    public function __construct()
    {
    }

    /**
     * @return void
     */
    public static function onActivate()
    {
        try {
            OrderRepository::createOrderColumns();
            self::addPaymentMethod();
            self::clearTmp();
        } catch (\Throwable $th) {
            self::handleError($th);
        } catch (\Exception $th) { // @phpstan-ignore-line | php5.6 compatibility
            self::handleError($th);
        }
    }

    /**
     * @return void
     */
    public static function onDeactivate()
    {
        try {
            self::disablePaymentMethod();
            self::clearTmp();
        } catch (\Throwable $th) {
            self::handleError($th);
        } catch (\Exception $th) { // @phpstan-ignore-line | php5.6 compatibility
            self::handleError($th);
        }
    }

    /**
     * @return void
     */
    private static function addPaymentMethod()
    {
        /** @var \OxidEsales\Eshop\Core\DbMetaDataHandler */
        $metaDataHandler = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);

        /** @var \OxidEsales\Eshop\Application\Model\Payment */
        $payment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
        if ($payment->load(self::PAYMENT_METHOD_ID)) {
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxactive = new \OxidEsales\Eshop\Core\Field(1);
            $payment->save();
        } else {
            $payment->setId(self::PAYMENT_METHOD_ID);

            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_DE_DESC);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxlongdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_DE_LONG_DESC);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxactive = new \OxidEsales\Eshop\Core\Field(1);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxfromamount = new \OxidEsales\Eshop\Core\Field(0);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxtoamount = new \OxidEsales\Eshop\Core\Field(1000000);
            $payment->save();
        }

        $languages = \OxidEsales\Eshop\Core\Registry::getLang()->getAllShopLanguageIds();

        if (in_array('de', $languages, true)) {
            $lang = strval(array_search('de', $languages, true));
            $payment->setLanguage($lang);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_DE_DESC);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxlongdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_DE_LONG_DESC);
            $payment->save();
        }

        if (in_array('en', $languages, true)) {
            $lang = strval(array_search('en', $languages, true));
            $payment->setLanguage($lang);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_EN_DESC);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxlongdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_EN_LONG_DESC);
            $payment->save();
        }

        if (in_array('fr', $languages, true)) {
            $lang = strval(array_search('fr', $languages, true));
            $payment->setLanguage($lang);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_FR_DESC);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxlongdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_FR_LONG_DESC);
            $payment->save();
        }

        if (in_array('nl', $languages, true)) {
            $lang = strval(array_search('nl', $languages, true));
            $payment->setLanguage($lang);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_NL_DESC);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxlongdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_NL_LONG_DESC);
            $payment->save();
        }

        if (in_array('es', $languages, true)) {
            $lang = strval(array_search('es', $languages, true));
            $payment->setLanguage($lang);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_ES_DESC);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxlongdesc = new \OxidEsales\Eshop\Core\Field(self::PAYMENT_METHOD_ES_LONG_DESC);
            $payment->save();
        }

        $metaDataHandler->updateViews();
    }

    /**
     * @return void
     */
    private static function disablePaymentMethod()
    {
        /** @var \OxidEsales\Eshop\Application\Model\Payment */
        $payment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
        if ($payment->load(self::PAYMENT_METHOD_ID)) {
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxactive = new \OxidEsales\Eshop\Core\Field(0);

            $payment->save();
        }
    }

    /**
     * @return void
     */
    private static function clearTmp()
    {
        $sTmpDir = getShopBasePath() . '/tmp/';
        $sSmartyDir = $sTmpDir . 'smarty/';

        /** @phpstan-ignore-next-line */
        foreach (glob($sTmpDir . '*.txt') as $sFileName) {
            unlink($sFileName);
        }
        /** @phpstan-ignore-next-line */
        foreach (glob($sSmartyDir . '*.php') as $sFileName) {
            unlink($sFileName);
        }
    }

    /**
     * @param \Throwable $error
     *
     * @return void
     */
    private static function handleError($error)
    {
        $container = ContainerFactory::getInstance()->getContainer();
        /** @var ErrorHandler */
        $errorHandler = $container->get(ErrorHandler::class);
        $errorHandler->handle($error);
    }
}
