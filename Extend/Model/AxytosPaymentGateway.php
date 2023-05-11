<?php

namespace Axytos\KaufAufRechnung_OXID6\Extend\Model;

use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\PluginConfigurationValidator;
use Axytos\ECommerce\Clients\Invoice\ShopActions;
use Axytos\KaufAufRechnung_OXID6\Core\InvoiceOrderContextFactory;
use Axytos\KaufAufRechnung_OXID6\Core\OrderCheckProcessStateMachine;
use Axytos\KaufAufRechnung_OXID6\ErrorReporting\ErrorHandler;
use Axytos\KaufAufRechnung_OXID6\Events\AxytosEvents;
use Axytos\KaufAufRechnung_OXID6\Extend\ServiceContainer;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;

class AxytosPaymentGateway extends AxytosPaymentGateway_parent
{
    use ServiceContainer;

    /** @phpstan-ignore-next-line
     * @var \Axytos\ECommerce\Clients\Invoice\PluginConfigurationValidator */
    private $pluginConfigurationValidator;
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface */
    private $invoiceClient;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\ErrorReporting\ErrorHandler */
    private $errorHandler;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\Core\InvoiceOrderContextFactory */
    private $invoiceOrderContextFactory;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\Core\OrderCheckProcessStateMachine */
    private $orderCheckProcessStateMachine;

    public function __construct()
    {
        parent::__construct();
        $this->pluginConfigurationValidator = $this->getServiceFromContainer(PluginConfigurationValidator::class);
        $this->invoiceClient = $this->getServiceFromContainer(InvoiceClientInterface::class);
        $this->errorHandler = $this->getServiceFromContainer(ErrorHandler::class);
        $this->invoiceOrderContextFactory = $this->getServiceFromContainer(InvoiceOrderContextFactory::class);
        $this->orderCheckProcessStateMachine = $this->getServiceFromContainer(OrderCheckProcessStateMachine::class);
    }

    public function executePayment($amount, &$oOrder)
    {
        /** @var Order */
        $order = $oOrder;
        $session = Registry::getSession();
        $sessionVariableKey = AxytosEvents::PAYMENT_METHOD_ID . '_error_id';

        if ($order->getPaymentType()->getFieldData("oxpaymentsid") !== AxytosEvents::PAYMENT_METHOD_ID) {
            $success = parent::executePayment($amount, $order);
            if ($success) {
                $session->deleteVariable($sessionVariableKey);
            }
            return $success;
        }

        try {
            /** @var AxytosOrder */
            $order = $oOrder;

            // add pre-check code here
            $invoiceOrderContext = $this->invoiceOrderContextFactory->getInvoiceOrderContext($order);

            $shopAction = $this->invoiceClient->precheck($invoiceOrderContext);

            if ($shopAction === ShopActions::CHANGE_PAYMENT_METHOD) {
                $config = Registry::getConfig();
                $utils = Registry::getUtils();
                $order->delete();
                $session->setVariable($sessionVariableKey, $shopAction);
                $utils->redirect($config->getSslShopUrl() . 'index.php?cl=payment&' . AxytosEvents::PAYMENT_METHOD_ID . '_error_id=' . ShopActions::CHANGE_PAYMENT_METHOD, false);
                return false;
            } else {
                $order->initializeOrderNumber();

                $this->orderCheckProcessStateMachine->setChecked($order);

                $this->invoiceClient->confirmOrder($invoiceOrderContext);

                $this->orderCheckProcessStateMachine->setConfirmed($order);

                $success = parent::executePayment($amount, $order);

                return $success;
            }
        } catch (\Throwable $th) {
            $this->orderCheckProcessStateMachine->setFailed($order);
            $this->errorHandler->handle($th);
            $order->delete();
            return false;
        } catch (\Exception $th) { // @phpstan-ignore-line bcause of php 5.6 compatibility
            $this->orderCheckProcessStateMachine->setFailed($order);
            $this->errorHandler->handle($th);
            $order->delete();
            return false;
        }
    }
}
