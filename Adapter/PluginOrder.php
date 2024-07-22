<?php

namespace Axytos\KaufAufRechnung_OXID6\Adapter;

use Axytos\KaufAufRechnung\Core\Plugin\Abstractions\PluginOrderInterface;
use Axytos\KaufAufRechnung_OXID6\Adapter\Information\BasketUpdateInformation;
use Axytos\KaufAufRechnung_OXID6\Adapter\Information\CancelInformation;
use Axytos\KaufAufRechnung_OXID6\Adapter\Information\CheckoutInformation;
use Axytos\KaufAufRechnung_OXID6\Adapter\Information\InvoiceInformation;
use Axytos\KaufAufRechnung_OXID6\Adapter\Information\RefundInformation;
use Axytos\KaufAufRechnung_OXID6\Adapter\Information\ShippingInformation;
use Axytos\KaufAufRechnung_OXID6\Adapter\Information\TrackingInformation;
use Axytos\KaufAufRechnung\Core\Plugin\Abstractions\Model\AxytosOrderStateInfo;
use Axytos\KaufAufRechnung_OXID6\Adapter\HashCalculation\HashCalculator;
use Axytos\KaufAufRechnung_OXID6\Adapter\Information\PaymentInformation;
use Axytos\KaufAufRechnung_OXID6\Core\InvoiceOrderContextFactory;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;

class PluginOrder implements PluginOrderInterface
{
    /**
     * @var \OxidEsales\Eshop\Application\Model\Order
     */
    private $order;

    /**
     * @var InvoiceOrderContextFactory
     */
    private $invoiceOrderContextFactory;

    /**
     * @var HashCalculator
     */
    private $hashCalculator;

    public function __construct(
        Order $order,
        InvoiceOrderContextFactory $invoiceOrderContextFactory,
        HashCalculator $hashCalculator
    ) {
        $this->order = $order;
        $this->invoiceOrderContextFactory = $invoiceOrderContextFactory;
        $this->hashCalculator = $hashCalculator;
    }

    public function getOrderNumber()
    {
        /** @var int */
        return $this->order->getFieldData('oxordernr');
    }

    public function loadState()
    {
        $state = strval($this->order->getFieldData("axytoskaufaufrechnungorderstate"));
        $data = strval($this->order->getFieldData("axytoskaufaufrechnungorderstatedata"));
        return new AxytosOrderStateInfo($state, $data);
    }

    public function saveState($state, $data = null)
    {
        /** @phpstan-ignore-next-line */
        $this->order->oxorder__axytoskaufaufrechnungorderstate = new Field($state);
        /** @phpstan-ignore-next-line */
        $this->order->oxorder__axytoskaufaufrechnungorderstatedata = new Field($data);
        $this->order->save();
    }

    public function freezeBasket()
    {
        $hash = $this->calculateOrderBasketHash();
        /** @phpstan-ignore-next-line */
        $this->order->oxorder__axytoskaufaufrechnungorderbaskethash = new Field($hash);
        $this->order->save();
    }

    public function checkoutInformation()
    {
        return new CheckoutInformation($this->invoiceOrderContextFactory->getInvoiceOrderContext($this->order));
    }

    public function hasBeenCanceled()
    {
        return boolval($this->order->getFieldData("oxstorno"));
    }

    public function cancelInformation()
    {
        return new CancelInformation($this->invoiceOrderContextFactory->getInvoiceOrderContext($this->order));
    }

    public function hasBeenInvoiced()
    {
        return strval($this->order->getFieldData("oxbillnr")) !== '';
    }

    public function invoiceInformation()
    {
        return new InvoiceInformation($this->invoiceOrderContextFactory->getInvoiceOrderContext($this->order));
    }

    public function hasBeenRefunded()
    {
        return false; // refunds are currently not a supported feature for oxid
    }

    public function refundInformation()
    {
        // should never be triggered!
        // refunds are currently not a supported feature for oxid
        return new RefundInformation($this->invoiceOrderContextFactory->getInvoiceOrderContext($this->order));
    }

    public function hasShippingReported()
    {
        /** @phpstan-ignore-next-line */
        return $this->order->getFieldData("axytoskaufaufrechnungshippingreported");
    }

    public function hasBeenShipped()
    {
        /** @var \OxidEsales\Eshop\Core\UtilsDate */
        $dateUtils = Registry::getUtilsDate();

        /** @var string */
        $sendDateRaw = $this->order->getFieldData("oxsenddate");
        $sendDate = $dateUtils->formatDBDate($sendDateRaw, true);

        return $sendDate !== "0000-00-00 00:00:00" &&
            $sendDate !== "-" &&
            $sendDate !== '';
    }

    public function saveHasShippingReported()
    {
        /** @phpstan-ignore-next-line */
        $this->order->oxorder__axytoskaufaufrechnungshippingreported = new Field(1);
        $this->order->save();
    }

    public function shippingInformation()
    {
        return new ShippingInformation($this->invoiceOrderContextFactory->getInvoiceOrderContext($this->order));
    }

    public function hasNewTrackingInformation()
    {
        /** @var string */
        $trackCode = $this->order->getFieldData("oxtrackcode");
        /** @var string */
        $reportedTrackingCode = $this->order->getFieldData("axytoskaufaufrechnungreportedtrackingcode");
        return $trackCode !== $reportedTrackingCode;
    }

    public function saveNewTrackingInformation()
    {
        /** @var string */
        $trackCode = $this->order->getFieldData("oxtrackcode");
        /** @phpstan-ignore-next-line */
        $this->order->oxorder__axytoskaufaufrechnungreportedtrackingcode = new Field($trackCode);
        $this->order->save();
    }

    public function trackingInformation()
    {
        return new TrackingInformation($this->invoiceOrderContextFactory->getInvoiceOrderContext($this->order));
    }

    public function hasBasketUpdates()
    {
        /** @var string */
        $oldHash = $this->order->getFieldData("axytoskaufaufrechnungorderbaskethash");
        $newHash = $this->calculateOrderBasketHash();
        return $newHash !== $oldHash;
    }

    public function saveBasketUpdatesReported()
    {
        $orderHash = $this->calculateOrderBasketHash();
        /** @phpstan-ignore-next-line */
        $this->order->oxorder__axytoskaufaufrechnungorderbaskethash = new Field($orderHash);
        $this->order->save();
    }

    public function basketUpdateInformation()
    {
        return new BasketUpdateInformation($this->invoiceOrderContextFactory->getInvoiceOrderContext($this->order));
    }

    public function saveHasBeenPaid()
    {
        // payment callbacks are currently not a supported feature for oxid
    }

    public function paymentInformation()
    {
        // should never be triggered!
        // payment callbacks are currently not a supported feature for oxid
        return new PaymentInformation($this->invoiceOrderContextFactory->getInvoiceOrderContext($this->order));
    }

    /**
     * @return string
     */
    private function calculateOrderBasketHash()
    {
        $basket = $this->checkoutInformation()->getBasket();
        return $this->hashCalculator->calculateBasketHash($basket);
    }
}