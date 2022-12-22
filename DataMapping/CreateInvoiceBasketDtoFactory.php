<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto;
use OxidEsales\Eshop\Application\Model\Order;

class CreateInvoiceBasketDtoFactory
{
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\CreateInvoiceBasketPositionDtoCollectionFactory
     */
    private $createInvoiceBasketPositionDtoCollectionFactory;
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\CreateInvoiceTaxGroupDtoCollectionFactory
     */
    private $createInvoiceTaxGroupDtoCollectionFactory;

    public function __construct(
        CreateInvoiceBasketPositionDtoCollectionFactory $createInvoiceBasketPositionDtoCollectionFactory,
        CreateInvoiceTaxGroupDtoCollectionFactory $createInvoiceTaxGroupDtoCollectionFactory
    ) {
        $this->createInvoiceBasketPositionDtoCollectionFactory = $createInvoiceBasketPositionDtoCollectionFactory;
        $this->createInvoiceTaxGroupDtoCollectionFactory = $createInvoiceTaxGroupDtoCollectionFactory;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     */
    public function create($order): CreateInvoiceBasketDto
    {
        $basket = new CreateInvoiceBasketDto();
        $basket->positions = $this->createInvoiceBasketPositionDtoCollectionFactory->create($order);
        $basket->taxGroups = $this->createInvoiceTaxGroupDtoCollectionFactory->create($order);
        $basket->grossTotal = floatval($order->getFieldData("oxtotalbrutsum"));
        $basket->netTotal = floatval($order->getFieldData("oxtotalnetsum"));
        return $basket;
    }
}
