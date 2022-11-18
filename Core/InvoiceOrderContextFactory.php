<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\Core;

use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\KaufAufRechnung_OXID6\Core\InvoiceOrderContext;
use Axytos\KaufAufRechnung_OXID6\DataMapping\BasketDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\CreateInvoiceBasketDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\CustomerDataDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\DeliveryAddressDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\InvoiceAddressDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\ShippingBasketPositionDtoCollectionFactory;
use OxidEsales\Eshop\Application\Model\Order;

class InvoiceOrderContextFactory
{
    private CustomerDataDtoFactory $customerDataDtoFactory;
    private DeliveryAddressDtoFactory $deliveryAddressDtoFactory;
    private InvoiceAddressDtoFactory $invoiceAddressDtoFactory;
    private BasketDtoFactory $basketDtoFactory;
    private CreateInvoiceBasketDtoFactory $createInvoiceBasketDtoFactory;
    private ShippingBasketPositionDtoCollectionFactory $shippingBasketPositionDtoCollectionFactory;

    public function __construct(
        CustomerDataDtoFactory $customerDataDtoFactory,
        InvoiceAddressDtoFactory $invoiceAddressDtoFactory,
        DeliveryAddressDtoFactory $deliveryAddressDtoFactory,
        BasketDtoFactory $basketDtoFactory,
        CreateInvoiceBasketDtoFactory $createInvoiceBasketDtoFactory,
        ShippingBasketPositionDtoCollectionFactory $shippingBasketPositionDtoCollectionFactory,
    ) {
        $this->customerDataDtoFactory = $customerDataDtoFactory;
        $this->invoiceAddressDtoFactory = $invoiceAddressDtoFactory;
        $this->deliveryAddressDtoFactory = $deliveryAddressDtoFactory;
        $this->basketDtoFactory = $basketDtoFactory;
        $this->createInvoiceBasketDtoFactory = $createInvoiceBasketDtoFactory;
        $this->shippingBasketPositionDtoCollectionFactory = $shippingBasketPositionDtoCollectionFactory;
    }

    public function getInvoiceOrderContext(
        Order $order
    ): InvoiceOrderContextInterface {
        return new InvoiceOrderContext(
            $order,
            $this->customerDataDtoFactory,
            $this->invoiceAddressDtoFactory,
            $this->deliveryAddressDtoFactory,
            $this->basketDtoFactory,
            $this->createInvoiceBasketDtoFactory,
            $this->shippingBasketPositionDtoCollectionFactory,
        );
    }
}
