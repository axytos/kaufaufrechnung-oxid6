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

        $countryQueryBuilder->select('oxobject2delivery.oxobjectid')
            ->from('oxobject2delivery')
            ->where('(oxdeliveryid = :oxdeliveryid) AND (oxtype = :rdfadeliveryset)')
            ->setParameters([
                ':oxdeliveryid' => $order->getFieldData("oxdeltype"),
                ':rdfadeliveryset' => "rdfadeliveryset"
            ]);

        $value = $countryQueryBuilder->execute()->fetchOne();

        /** @phpstan-ignore-next-line */
        return strval($value) ?: "";
    }
}
