<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketPositionDtoCollection;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Model\ListModel;

class CreateInvoiceBasketPositionDtoCollectionFactory
{
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\CreateInvoiceBasketPositionDtoFactory
     */
    private $createInvoiceBasketPositionDtoFactory;

    public function __construct(CreateInvoiceBasketPositionDtoFactory $createInvoiceBasketPositionDtoFactory)
    {
        $this->createInvoiceBasketPositionDtoFactory = $createInvoiceBasketPositionDtoFactory;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return \Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketPositionDtoCollection
     */
    public function create($order)
    {
        /** @var ListModel */
        $orderArticles = $order->getOrderArticles();
        $positions = array_map([$this->createInvoiceBasketPositionDtoFactory, 'create'], array_values($orderArticles->getArray()));
        $shippingPosition = $this->createInvoiceBasketPositionDtoFactory->createShippingPosition($order);
        array_push($positions, $shippingPosition);

        return new CreateInvoiceBasketPositionDtoCollection(...$positions);
    }
}
