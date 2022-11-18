<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDtoCollection;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Model\ListModel;

class ShippingBasketPositionDtoCollectionFactory
{
    private ShippingBasketPositionDtoFactory $shippingBasketPositionDtoFactory;

    public function __construct(ShippingBasketPositionDtoFactory $shippingBasketPositionDtoFactory)
    {
        $this->shippingBasketPositionDtoFactory = $shippingBasketPositionDtoFactory;
    }

    public function create(Order $order): ShippingBasketPositionDtoCollection
    {
        /** @var ListModel */
        $orderArticles = $order->getOrderArticles();
        $positions = array_map([$this->shippingBasketPositionDtoFactory, 'create'], array_values($orderArticles->getArray()));

        array_push($positions, $this->shippingBasketPositionDtoFactory->createShippingPosition());

        return new ShippingBasketPositionDtoCollection(...$positions);
    }
}
