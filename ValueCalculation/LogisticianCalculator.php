<?php

namespace Axytos\KaufAufRechnung_OXID6\ValueCalculation;

use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class LogisticianCalculator
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return string
     */
    public function calculate($order)
    {
        /** @phpstan-ignore-next-line */
        $countryQueryBuilder = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
            ->create();

        $countryQueryBuilder->select('oxdeliveryset.oxtitle')
            ->from('oxdeliveryset')
            ->where('oxid = :oxdeliveryid')
            ->setParameters([
                ':oxdeliveryid' => $order->getFieldData("oxdeltype"),
            ]);

        $value = $countryQueryBuilder->execute()->fetchOne();

        return strval($value) !== '' ? strval($value) : "";
    }
}
