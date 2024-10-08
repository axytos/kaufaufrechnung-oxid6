<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDtoCollection;

class ShippingBasketPositionDtoCollectionFactory
{
    /**
     * @var ShippingBasketPositionDtoFactory
     */
    private $shippingBasketPositionDtoFactory;

    public function __construct(ShippingBasketPositionDtoFactory $shippingBasketPositionDtoFactory)
    {
        $this->shippingBasketPositionDtoFactory = $shippingBasketPositionDtoFactory;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     *
     * @return ShippingBasketPositionDtoCollection
     */
    public function create($order)
    {
        /** @var \OxidEsales\Eshop\Core\Model\ListModel */
        $orderArticles = $order->getOrderArticles();
        $positions = array_map([$this->shippingBasketPositionDtoFactory, 'create'], array_values($orderArticles->getArray()));

        array_push($positions, $this->shippingBasketPositionDtoFactory->createShippingPosition());

        return new ShippingBasketPositionDtoCollection(...$positions);
    }
}
