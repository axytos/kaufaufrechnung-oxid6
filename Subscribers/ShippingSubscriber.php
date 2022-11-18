<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\Subscribers;

use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\PluginConfigurationValidator;
use Axytos\KaufAufRechnung_OXID6\Core\InvoiceOrderContextFactory;
use Axytos\KaufAufRechnung_OXID6\ErrorReporting\ErrorHandler;
use Axytos\KaufAufRechnung_OXID6\Events\AxytosEvents;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\EshopCommunity\Internal\Framework\Event\AbstractShopAwareEventSubscriber;
use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\BeforeModelUpdateEvent;

class ShippingSubscriber extends AbstractShopAwareEventSubscriber
{
    private InvoiceClientInterface $invoiceClient;
    private InvoiceOrderContextFactory $invoiceOrderContextFactory;
    private PluginConfigurationValidator $pluginConfigurationValidator;
    private ErrorHandler $errorHandler;

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

    public function beforeModelUpdate(BeforeModelUpdateEvent $event): void
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

            if (
                $model->getFieldData("oxsenddate") === "0000-00-00 00:00:00" ||
                empty($model->getFieldData("oxsenddate")) ||
                $model->getFieldData("oxsenddate") === "-"
            ) {
                return;
            }

            $order_id = $model->getId();

            /** @var Order */
            $order = oxNew(Order::class); // @phpstan-ignore-line
            $order->load($order_id);

            if ($model->getFieldData("oxsenddate") === $order->getFieldData("oxsenddate")) {
                return;
            }

            $context = $this->invoiceOrderContextFactory->getInvoiceOrderContext($model);
            $this->invoiceClient->reportShipping($context);
        } catch (\Throwable $th) {
            $this->errorHandler->handle($th);
        }
    }

    public static function getSubscribedEvents()
    {
        return [BeforeModelUpdateEvent::class => 'beforeModelUpdate'];
    }
}
