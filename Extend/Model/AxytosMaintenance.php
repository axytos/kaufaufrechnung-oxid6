<?php

namespace Axytos\KaufAufRechnung_OXID6\Extend\Model;

use Axytos\KaufAufRechnung_OXID6\Extend\ServiceContainer;
use Axytos\KaufAufRechnung_OXID6\OrderSync\OrderSyncCronJob;

class AxytosMaintenance extends AxytosMaintenance_parent
{
    use ServiceContainer;

    /**
     * @return void
     */
    public function execute()
    {
        parent::execute();

        /** @var OrderSyncCronJob */
        $orderSyncCronJob = $this->getServiceFromContainer(OrderSyncCronJob::class);
        $orderSyncCronJob->execute();
    }
}
