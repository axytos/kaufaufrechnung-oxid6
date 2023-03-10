<?php

namespace Axytos\KaufAufRechnung_OXID6\Subscribers;

use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\PluginConfigurationValidator;
use Axytos\KaufAufRechnung_OXID6\Core\InvoiceOrderContextFactory;
use Axytos\KaufAufRechnung_OXID6\ErrorReporting\ErrorHandler;
use Axytos\KaufAufRechnung_OXID6\Events\AxytosEvents;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\EshopCommunity\Internal\Framework\Event\AbstractShopAwareEventSubscriber;
use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\BeforeModelUpdateEvent;

class CancelSubscriber extends AbstractShopAwareEventSubscriber
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface
     */
    private $invoiceClient;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\Core\InvoiceOrderContextFactory
     */
    private $invoiceOrderContextFactory;
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\PluginConfigurationValidator
     */
    private $pluginConfigurationValidator;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\ErrorReporting\ErrorHandler
     */
    private $errorHandler;

    public function __construct(
        InvoiceClientInterface $invoiceClient,
        InvoiceOrderContextFactory $invoiceOrderContextFactory,
        PluginConfigurationValidator $pluginConfigurationValidator,
        ErrorHandler $errorHandler
    ) {
        $this->invoiceClient = $invoiceClient;
        $this->invoiceOrderContextFactory = $invoiceOrderContextFactory;
        $this->pluginConfigurationValidator = $pluginConfigurationValidator;
        $this->errorHandler = $errorHandler;
    }

    /**
     * @param \OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\BeforeModelUpdateEvent $event
     * @return void
     */
    public function beforeModelUpdate($event)
    {
        try {
            if (!($event->getModel() instanceof Order)) {
                return;
            }
            /** @var Order */
            $model = $event->getModel();
            $payment_method_id = $model->getPaymentType()->getFieldData("oxpaymentsid");
            if ($payment_method_id !== AxytosEvents::PAYMENT_METHOD_ID) {
                return;
            }

            if ($this->pluginConfigurationValidator->isInvalid()) {
                return;
            }

            if ($model->getFieldData("oxstorno") !== 1) {
                return;
            }

            $order_id = $model->getId();

            /** @var Order */
            $order = oxNew(Order::class); // @phpstan-ignore-line
            $order->load($order_id);

            if ($model->getFieldData("oxstorno") === intval($order->getFieldData("oxstorno"))) {
                return;
            }

            $context = $this->invoiceOrderContextFactory->getInvoiceOrderContext($model);
            $this->invoiceClient->cancelOrder($context);
        } catch (\Throwable $th) {
            $this->errorHandler->handle($th);
        } catch (\Exception $th) { // @phpstan-ignore-line bcause of php 5.6 compatibility
            $this->errorHandler->handle($th);
        }
    }

    public static function getSubscribedEvents()
    {
        return [BeforeModelUpdateEvent::class => 'beforeModelUpdate'];
    }
}
