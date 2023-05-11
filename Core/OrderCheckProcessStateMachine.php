<?php

namespace Axytos\KaufAufRechnung_OXID6\Core;

use Axytos\ECommerce\Order\OrderCheckProcessStates;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Field;

class OrderCheckProcessStateMachine
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return string|null
     */
    public function getState($order)
    {
        /** @phpstan-ignore-next-line */
        return $order->oxorder__axytoskaufaufrechnungordercheckprocessstatus->value;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return void
     */
    public function setUnchecked($order)
    {
        $this->updateState($order, OrderCheckProcessStates::UNCHECKED);
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return void
     */
    public function setChecked($order)
    {
        $this->updateState($order, OrderCheckProcessStates::CHECKED);
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return void
     */
    public function setConfirmed($order)
    {
        $this->updateState($order, OrderCheckProcessStates::CONFIRMED);
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return void
     */
    public function setFailed($order)
    {
        $this->updateState($order, OrderCheckProcessStates::FAILED);
    }

    /**
     * @return void
     * @param string $orderCheckProcessState
     */
    private function updateState(Order $order, $orderCheckProcessState)
    {
        $orderCheckProcessState = (string) $orderCheckProcessState;
        /** @phpstan-ignore-next-line */
        $order->oxorder__axytoskaufaufrechnungordercheckprocessstatus = new Field($orderCheckProcessState);
        $order->save();
    }
}
