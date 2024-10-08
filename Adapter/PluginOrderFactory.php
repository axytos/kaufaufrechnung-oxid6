<?php

namespace Axytos\KaufAufRechnung_OXID6\Adapter;

use Axytos\KaufAufRechnung_OXID6\Adapter\HashCalculation\HashCalculator;
use Axytos\KaufAufRechnung_OXID6\Core\InvoiceOrderContextFactory;

class PluginOrderFactory
{
    /**
     * @var InvoiceOrderContextFactory
     */
    private $invoiceOrderContextFactory;

    /**
     * @var HashCalculator
     */
    private $hashCalculator;

    public function __construct(
        InvoiceOrderContextFactory $invoiceOrderContextFactory,
        HashCalculator $hashCalculator
    ) {
        $this->invoiceOrderContextFactory = $invoiceOrderContextFactory;
        $this->hashCalculator = $hashCalculator;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     *
     * @return \Axytos\KaufAufRechnung\Core\Plugin\Abstractions\PluginOrderInterface
     */
    public function create($order)
    {
        return new PluginOrder(
            $order,
            $this->invoiceOrderContextFactory,
            $this->hashCalculator
        );
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order[] $orders
     *
     * @return \Axytos\KaufAufRechnung\Core\Plugin\Abstractions\PluginOrderInterface[]
     */
    public function createMany($orders)
    {
        return array_map([$this, 'create'], $orders);
    }
}
