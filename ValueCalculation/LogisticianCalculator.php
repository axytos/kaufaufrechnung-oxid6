<?php

namespace Axytos\KaufAufRechnung_OXID6\ValueCalculation;

use Axytos\KaufAufRechnung_OXID6\DataAbstractionLayer\OrderRepository;
use OxidEsales\Eshop\Application\Model\Order;

class LogisticianCalculator
{
    /**
     * @var \Axytos\KaufAufRechnung_OXID6\DataAbstractionLayer\OrderRepository
     */
    private $orderRepository;

    /**
     * @param \Axytos\KaufAufRechnung_OXID6\DataAbstractionLayer\OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return string
     */
    public function calculate($order)
    {
        return $this->orderRepository->findLogistician($order);
    }
}
