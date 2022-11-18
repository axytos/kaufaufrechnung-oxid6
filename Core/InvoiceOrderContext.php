<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\Core;

use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\DataTransferObjects\BasketDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketDto;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection;
use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDtoCollection;
use Axytos\KaufAufRechnung_OXID6\DataMapping\BasketDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\CreateInvoiceBasketDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\CustomerDataDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\DeliveryAddressDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\InvoiceAddressDtoFactory;
use Axytos\KaufAufRechnung_OXID6\DataMapping\ShippingBasketPositionDtoCollectionFactory;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Field;

class InvoiceOrderContext implements InvoiceOrderContextInterface
{
    private Order $order;
    private CustomerDataDtoFactory $customerDataDtoFactory;
    private InvoiceAddressDtoFactory $invoiceAddressDtoFactory;
    private DeliveryAddressDtoFactory $deliveryAddressDtoFactory;
    private BasketDtoFactory $basketDtoFactory;
    private CreateInvoiceBasketDtoFactory $createInvoiceBasketDtoFactory;
    private ShippingBasketPositionDtoCollectionFactory $shippingBasketPositionDtoCollectionFactory;

    public function __construct(
        Order $order,
        CustomerDataDtoFactory $customerDataDtoFactory,
        InvoiceAddressDtoFactory $invoiceAddressDtoFactory,
        DeliveryAddressDtoFactory $deliveryAddressDtoFactory,
        BasketDtoFactory $basketDtoFactory,
        CreateInvoiceBasketDtoFactory $createInvoiceBasketDtoFactory,
        ShippingBasketPositionDtoCollectionFactory $shippingBasketPositionDtoCollectionFactory
    ) {
        $this->order = $order;
        $this->customerDataDtoFactory = $customerDataDtoFactory;
        $this->invoiceAddressDtoFactory = $invoiceAddressDtoFactory;
        $this->deliveryAddressDtoFactory = $deliveryAddressDtoFactory;
        $this->basketDtoFactory = $basketDtoFactory;
        $this->createInvoiceBasketDtoFactory = $createInvoiceBasketDtoFactory;
        $this->shippingBasketPositionDtoCollectionFactory = $shippingBasketPositionDtoCollectionFactory;
    }

    public function getOrderNumber(): string
    {
        return $this->order->getId();
    }

    public function getOrderInvoiceNumber(): string
    {
        /** @var string */
        return $this->order->getFieldData("oxbillnr");
    }

    public function getOrderDateTime(): DateTimeInterface
    {
        /** @phpstan-ignore-next-line */
        return DateTimeImmutable::createFromFormat('Y-m-d G:i:s', $this->order->getFieldData("oxorderdate"));
    }

    public function getPersonalData(): CustomerDataDto
    {
        return $this->customerDataDtoFactory->create($this->order);
    }

    public function getInvoiceAddress(): InvoiceAddressDto
    {
        return $this->invoiceAddressDtoFactory->create($this->order);
    }

    public function getDeliveryAddress(): DeliveryAddressDto
    {
        return $this->deliveryAddressDtoFactory->create($this->order);
    }

    public function getBasket(): BasketDto
    {
        return $this->basketDtoFactory->create($this->order);
    }

    public function getRefundBasket(): RefundBasketDto
    {
        return new RefundBasketDto();
    }

    public function getCreateInvoiceBasket(): CreateInvoiceBasketDto
    {
        return $this->createInvoiceBasketDtoFactory->create($this->order);
    }

    public function getShippingBasketPositions(): ShippingBasketPositionDtoCollection
    {
        return $this->shippingBasketPositionDtoCollectionFactory->create($this->order);
    }

    public function getReturnPositions(): ReturnPositionModelDtoCollection
    {
        return new ReturnPositionModelDtoCollection();
    }

    public function getPreCheckResponseData(): array
    {
        /** @phpstan-ignore-next-line */
        return unserialize(base64_decode($this->order->oxorder__axytoskaufaufrechnungorderprecheckresult->value));
    }

    public function setPreCheckResponseData(array $data): void
    {
        /** @phpstan-ignore-next-line */
        $this->order->oxorder__axytoskaufaufrechnungorderprecheckresult = new Field(base64_encode(serialize($data)));
        $this->order->save();
    }
}
