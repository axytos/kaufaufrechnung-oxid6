<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketPositionDtoCollection;

class CreateInvoiceBasketPositionDtoCollectionFactory
{
    /**
     * @var CreateInvoiceBasketPositionDtoFactory
     */
    private $createInvoiceBasketPositionDtoFactory;

    public function __construct(CreateInvoiceBasketPositionDtoFactory $createInvoiceBasketPositionDtoFactory)
    {
        $this->createInvoiceBasketPositionDtoFactory = $createInvoiceBasketPositionDtoFactory;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     *
     * @return CreateInvoiceBasketPositionDtoCollection
     */
    public function create($order)
    {
        /** @var \OxidEsales\Eshop\Core\Model\ListModel */
        $orderArticles = $order->getOrderArticles();
        $positions = array_map([$this->createInvoiceBasketPositionDtoFactory, 'create'], array_values($orderArticles->getArray()));

        $voucherPosition = $this->createInvoiceBasketPositionDtoFactory->createVoucherPosition($order, $positions);
        if (!is_null($voucherPosition)) {
            array_push($positions, $voucherPosition);
        }

        $shippingPosition = $this->createInvoiceBasketPositionDtoFactory->createShippingPosition($order);
        array_push($positions, $shippingPosition);

        return new CreateInvoiceBasketPositionDtoCollection(...$positions);
    }
}
