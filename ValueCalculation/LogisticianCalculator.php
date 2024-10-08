<?php

namespace Axytos\KaufAufRechnung_OXID6\ValueCalculation;

use Axytos\KaufAufRechnung_OXID6\DataAbstractionLayer\OrderRepository;

class LogisticianCalculator
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     *
     * @return string
     */
    public function calculate($order)
    {
        return $this->orderRepository->findLogistician($order);
    }
}
