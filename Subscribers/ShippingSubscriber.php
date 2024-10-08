<?php

namespace Axytos\KaufAufRechnung_OXID6\Subscribers;

use OxidEsales\EshopCommunity\Internal\Framework\Event\AbstractShopAwareEventSubscriber;
use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\BeforeModelUpdateEvent;

// ========================
// DO NOT REMOVE THIS CLASS
// ========================
// OXID6 has problems properly removing registered subscribers
//  from it's dependency injection during module updates via composer
//  and will break the shop
// So we have to keep this class in the codebase to be safe
class ShippingSubscriber extends AbstractShopAwareEventSubscriber
{
    /**
     * @param BeforeModelUpdateEvent $event
     *
     * @return void
     */
    public function beforeModelUpdate($event)
    {
        // DO NOTHING
    }

    public static function getSubscribedEvents()
    {
        return [BeforeModelUpdateEvent::class => 'beforeModelUpdate'];
    }
}
