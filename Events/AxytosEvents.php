<?php

namespace Axytos\KaufAufRechnung_OXID6\Events;

use Axytos\KaufAufRechnung_OXID6\ErrorReporting\ErrorHandler;
use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use Throwable;

class AxytosEvents
{
    const PAYMENT_METHOD_ID = "axytos_kaufaufrechnung";
    const PAYMENT_METHOD_DE_DESC = "Kauf auf Rechnung";
    const PAYMENT_METHOD_DE_LONG_DESC = "Sie zahlen bequem die Rechnung, sobald Sie die Ware erhalten haben, innerhalb der Zahlfrist";
    const PAYMENT_METHOD_EN_DESC = "Buy Now Pay Later";
    const PAYMENT_METHOD_EN_LONG_DESC = "You conveniently pay the invoice as soon as you receive the goods, within the payment period";

    public function __construct()
    {
    }

    /**
     * @return void
     */
    public static function onActivate()
    {
        try {
            self::addPaymentMethod();
            self::addOrderCheckProcessStatus();
            self::addOrderPreCheckResult();
        } catch (\Throwable $th) {
            self::handleError($th);
        } catch (\Exception $th) { // @phpstan-ignore-line bcause of php 5.6 compatibility
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
        } catch (\Throwable $th) {
            self::handleError($th);
        } catch (\Exception $th) { // @phpstan-ignore-line bcause of php 5.6 compatibility
            self::handleError($th);
        }
    }

    /**
     * @return void
     * @param string $sqlStatement
     */
    private static function executeSQLStatement($sqlStatement)
    {
        $sqlStatement = (string) $sqlStatement;
        $container = ContainerFactory::getInstance()->getContainer();
        /** @var QueryBuilderFactoryInterface */
        $queryBuilderFactory = $container->get(QueryBuilderFactoryInterface::class);
        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder->getConnection()->executeStatement($sqlStatement);
    }

    /**
     * @return void
     */
    private static function addOrderCheckProcessStatus()
    {
        $addFieldSQL = "ALTER TABLE oxorder ADD COLUMN IF NOT EXISTS AXYTOSKAUFAUFRECHNUNGORDERCHECKPROCESSSTATUS VARCHAR(128) DEFAULT 'UNCHECKED'";

        self::executeSQLStatement($addFieldSQL);
    }

    /**
     * @return void
     */
    private static function addOrderPreCheckResult()
    {
        $addFieldSQL = "ALTER TABLE oxorder ADD COLUMN IF NOT EXISTS AXYTOSKAUFAUFRECHNUNGORDERPRECHECKRESULT TEXT";

        self::executeSQLStatement($addFieldSQL);
    }

    /**
     * @return void
     */
    private static function addPaymentMethod()
    {
        /**
         * @var DbMetaDataHandler
         * @phpstan-ignore-next-line
         */
        $metaDataHandler = oxNew(DbMetaDataHandler::class);

        /**
         * @var Payment
         * @phpstan-ignore-next-line
         */
        $payment = oxNew(Payment::class);

        if ($payment->load(self::PAYMENT_METHOD_ID)) {
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxactive = new Field(1);
            $payment->save();
        } else {
            $payment->setId(self::PAYMENT_METHOD_ID);

            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxdesc = new Field(self::PAYMENT_METHOD_DE_DESC);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxlongdesc = new Field(self::PAYMENT_METHOD_DE_LONG_DESC);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxactive = new Field(1);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxfromamount = new Field(0);
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxtoamount = new Field(1000000);
            $payment->save();

            $languages = Registry::getLang()->getAllShopLanguageIds();

            if (in_array("de", $languages)) {
                $lang = strval(array_search("de", $languages));
                $payment->setLanguage($lang);
                /** @phpstan-ignore-next-line */
                $payment->oxpayments__oxdesc = new Field(self::PAYMENT_METHOD_DE_DESC);
                /** @phpstan-ignore-next-line */
                $payment->oxpayments__oxlongdesc = new Field(self::PAYMENT_METHOD_DE_LONG_DESC);
                $payment->save();
            }

            if (in_array("en", $languages)) {
                $lang = strval(array_search("en", $languages));
                $payment->setLanguage($lang);
                /** @phpstan-ignore-next-line */
                $payment->oxpayments__oxdesc = new Field(self::PAYMENT_METHOD_EN_DESC);
                /** @phpstan-ignore-next-line */
                $payment->oxpayments__oxlongdesc = new Field(self::PAYMENT_METHOD_EN_LONG_DESC);
                $payment->save();
            }
        }

        $metaDataHandler->updateViews();
    }

    /**
     * @return void
     */
    private static function disablePaymentMethod()
    {
        /**
         * @var Payment
         * @phpstan-ignore-next-line
         */
        $payment = oxNew(Payment::class);
        if ($payment->load(self::PAYMENT_METHOD_ID)) {
            /** @phpstan-ignore-next-line */
            $payment->oxpayments__oxactive = new Field(0);

            $payment->save();
        }
    }

    /**
     * @return void
     * @param \Throwable $error
     */
    private static function handleError($error)
    {
        $container = ContainerFactory::getInstance()->getContainer();
        /** @var ErrorHandler */
        $errorHandler = $container->get(ErrorHandler::class);
        $errorHandler->handle($error);
    }
}
