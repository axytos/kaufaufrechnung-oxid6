<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\Extend\Model;

use Axytos\ECommerce\Clients\Invoice\PluginConfigurationValidator;
use Axytos\KaufAufRechnung_OXID6\ErrorReporting\ErrorHandler;
use Axytos\KaufAufRechnung_OXID6\Events\AxytosEvents;
use Axytos\KaufAufRechnung_OXID6\Extend\ServiceContainer;

class AxytosPaymentList extends AxytosPaymentList_parent
{
    use ServiceContainer;

    public function getPaymentList($sShipSetId, $dPrice, $oUser = null)
    {
        try {
            $paymentList = parent::getPaymentList($sShipSetId, $dPrice, $oUser);

            $pluginConfigurationValidator = $this->getServiceFromContainer(PluginConfigurationValidator::class);
            if ($pluginConfigurationValidator->isInvalid()) {
                unset($paymentList[AxytosEvents::PAYMENT_METHOD_ID]);
            }

            return $paymentList;
        } catch (\Throwable $th) {
            /** @var ErrorHandler */
            $errorHandler = $this->getServiceFromContainer(ErrorHandler::class);
            $errorHandler->handle($th);

            try {
                // retry, error might not originate from parent
                return parent::getPaymentList($sShipSetId, $dPrice, $oUser);
            } catch (\Throwable $th) {
                return [];
            }
        }
    }
}
