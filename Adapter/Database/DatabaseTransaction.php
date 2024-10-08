<?php

namespace Axytos\KaufAufRechnung_OXID6\Adapter\Database;

use Axytos\KaufAufRechnung\Core\Plugin\Abstractions\Database\DatabaseTransactionInterface;
use Axytos\KaufAufRechnung_OXID6\DataAbstractionLayer\OrderRepository;

class DatabaseTransaction implements DatabaseTransactionInterface
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function begin()
    {
        $this->orderRepository->startTransaction();
    }

    public function commit()
    {
        $this->orderRepository->commitTransaction();
    }

    public function rollback()
    {
        $this->orderRepository->rollbackTransaction();
    }
}
