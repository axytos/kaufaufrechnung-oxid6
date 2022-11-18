<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\Core;

use Axytos\ECommerce\Order\OrderCheckProcessStates;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Field;

class OrderCheckProcessStateMachine
{
    public function getState(Order $order): ?string
    {
        /** @phpstan-ignore-next-line */
        return $order->oxorder__axytoskaufaufrechnungordercheckprocessstatus->value;
    }

    public function setUnchecked(Order $order): void
    {
        $this->updateState($order, OrderCheckProcessStates::UNCHECKED);
    }

    public function setChecked(Order $order): void
    {
        $this->updateState($order, OrderCheckProcessStates::CHECKED);
    }

    public function setConfirmed(Order $order): void
    {
        $this->updateState($order, OrderCheckProcessStates::CONFIRMED);
    }

    public function setFailed(Order $order): void
    {
        $this->updateState($order, OrderCheckProcessStates::FAILED);
    }

    private function updateState(Order $order, string $orderCheckProcessState): void
    {
        /** @phpstan-ignore-next-line */
        $order->oxorder__axytoskaufaufrechnungordercheckprocessstatus = new Field($orderCheckProcessState);
        /** @phpstan-ignore-next-line */
        $order->save();
    }
}
