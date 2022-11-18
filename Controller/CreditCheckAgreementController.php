<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\Controller;

use Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface;
use Axytos\KaufAufRechnung_OXID6\ErrorReporting\ErrorHandler;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;

class CreditCheckAgreementController extends FrontendController
{
    protected $_sThisTemplate = "credit_check_agreement.tpl";

    public function getCreditCheckAgreement(): string
    {
        try {
            /** @var CheckoutClientInterface */
            $checkoutClient =  ContainerFactory::getInstance()
                ->getContainer()
                ->get(CheckoutClientInterface::class);
            return $checkoutClient->getCreditCheckAgreementInfo();
        } catch (\Throwable $th) {
            /** @var ErrorHandler */
            $errorHandler =  ContainerFactory::getInstance()
                ->getContainer()
                ->get(ErrorHandler::class);
            $errorHandler->handle($th);
            return '';
        }
    }
}
