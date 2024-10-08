<?php

namespace Axytos\KaufAufRechnung_OXID6\Adapter\Information;

use Axytos\KaufAufRechnung\Core\Plugin\Abstractions\Information\InvoiceInformationInterface;
use Axytos\KaufAufRechnung_OXID6\Adapter\Information\Invoice\Basket;
use Axytos\KaufAufRechnung_OXID6\Core\InvoiceOrderContext;

class InvoiceInformation implements InvoiceInformationInterface
{
    /**
     * @var InvoiceOrderContext
     */
    private $invoiceOrderContext;

    public function __construct(InvoiceOrderContext $invoiceOrderContext)
    {
        $this->invoiceOrderContext = $invoiceOrderContext;
    }

    public function getOrderNumber()
    {
        return $this->invoiceOrderContext->getOrderNumber();
    }

    public function getInvoiceNumber()
    {
        return $this->invoiceOrderContext->getOrderInvoiceNumber();
    }

    public function getBasket()
    {
        $basket = $this->invoiceOrderContext->getCreateInvoiceBasket();

        return new Basket($basket);
    }
}
