<?php

namespace Axytos\KaufAufRechnung_OXID6\Core;

use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\KaufAufRechnung_OXID6\Core\InvoiceOrderContext;
use Axytos\KaufAufRechnung_OXID6\DataMapping\BasketDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\CreateInvoiceBasketDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\CustomerDataDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\DeliveryAddressDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\InvoiceAddressDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\ShippingBasketPositionDtoCollectionFactory;
use Axytos\KaufAufRechnung_OXID6\ValueCalculation\LogisticianCalculator;
use Axytos\KaufAufRechnung_OXID6\ValueCalculation\TrackingIdCalculator;
use OxidEsales\Eshop\Application\Model\Order;

class InvoiceOrderContextFactory
{
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\CustomerDataDtoFactory
     */
    private $customerDataDtoFactory;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\DeliveryAddressDtoFactory
     */
    private $deliveryAddressDtoFactory;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\InvoiceAddressDtoFactory
     */
    private $invoiceAddressDtoFactory;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\BasketDtoFactory
     */
    private $basketDtoFactory;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\CreateInvoiceBasketDtoFactory
     */
    private $createInvoiceBasketDtoFactory;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\ShippingBasketPositionDtoCollectionFactory
     */
    private $shippingBasketPositionDtoCollectionFactory;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\ValueCalculation\TrackingIdCalculator
     */
    private $trackingIdCalculator;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\ValueCalculation\LogisticianCalculator
     */
    private $logisticianCalculator;

    public function __construct(
        CustomerDataDtoFactory $customerDataDtoFactory,
        InvoiceAddressDtoFactory $invoiceAddressDtoFactory,
        DeliveryAddressDtoFactory $deliveryAddressDtoFactory,
        BasketDtoFactory $basketDtoFactory,
        CreateInvoiceBasketDtoFactory $createInvoiceBasketDtoFactory,
        ShippingBasketPositionDtoCollectionFactory $shippingBasketPositionDtoCollectionFactory,
        TrackingIdCalculator $trackingIdCalculator,
        LogisticianCalculator $logisticianCalculator
    ) {
        $this->customerDataDtoFactory = $customerDataDtoFactory;
        $this->invoiceAddressDtoFactory = $invoiceAddressDtoFactory;
        $this->deliveryAddressDtoFactory = $deliveryAddressDtoFactory;
        $this->basketDtoFactory = $basketDtoFactory;
        $this->createInvoiceBasketDtoFactory = $createInvoiceBasketDtoFactory;
        $this->shippingBasketPositionDtoCollectionFactory = $shippingBasketPositionDtoCollectionFactory;
        $this->trackingIdCalculator = $trackingIdCalculator;
        $this->logisticianCalculator = $logisticianCalculator;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getInvoiceOrderContext(
        $order
    ) {
        return new InvoiceOrderContext($order, $this->customerDataDtoFactory, $this->invoiceAddressDtoFactory, $this->deliveryAddressDtoFactory, $this->basketDtoFactory, $this->createInvoiceBasketDtoFactory, $this->shippingBasketPositionDtoCollectionFactory, $this->trackingIdCalculator, $this->logisticianCalculator);
    }
}
