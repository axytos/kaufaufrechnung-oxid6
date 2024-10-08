<?php

namespace Axytos\KaufAufRechnung_OXID6\Controller;

use Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface;
use Axytos\KaufAufRechnung_OXID6\DependencyInjection\ContainerFactory;
use Axytos\KaufAufRechnung_OXID6\ErrorReporting\ErrorHandler;
use OxidEsales\Eshop\Application\Controller\FrontendController;

class CreditCheckAgreementController extends FrontendController
{
    protected $_sThisTemplate = 'credit_check_agreement.tpl';

    /**
     * @return string
     */
    public function getCreditCheckAgreement()
    {
        try {
            /** @var CheckoutClientInterface */
            $checkoutClient = ContainerFactory::getInstance()
                ->getContainer()
                ->get(CheckoutClientInterface::class)
            ;

            return $checkoutClient->getCreditCheckAgreementInfo();
        } catch (\Throwable $th) {
            /** @var ErrorHandler */
            $errorHandler = ContainerFactory::getInstance()
                ->getContainer()
                ->get(ErrorHandler::class)
            ;
            $errorHandler->handle($th);

            return '';
        } catch (\Exception $th) { // @phpstan-ignore-line bcause of php 5.6 compatibility
            /** @var ErrorHandler */
            $errorHandler = ContainerFactory::getInstance()
                ->getContainer()
                ->get(ErrorHandler::class)
            ;
            $errorHandler->handle($th);

            return '';
        }
    }
}
