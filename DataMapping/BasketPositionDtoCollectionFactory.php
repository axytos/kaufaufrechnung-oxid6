<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\BasketPositionDtoCollection;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Model\ListModel;

class BasketPositionDtoCollectionFactory
{
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataMapping\BasketPositionDtoFactory
     */
    private $basketPositionDtoFactory;

    public function __construct(BasketPositionDtoFactory $basketPositionDtoFactory)
    {
        $this->basketPositionDtoFactory = $basketPositionDtoFactory;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     */
    public function create($order): BasketPositionDtoCollection
    {
        /** @var ListModel */
        $orderArticles = $order->getOrderArticles();
        $positions = array_map([$this->basketPositionDtoFactory, 'create'], array_values($orderArticles->getArray()));
        $shippingPosition = $this->basketPositionDtoFactory->createShippingPosition($order);
        array_push($positions, $shippingPosition);

        return new BasketPositionDtoCollection(...$positions);
    }
}
