<?php

namespace Axytos\KaufAufRechnung_OXID6\ValueCalculation;

class DeliveryWeightCalculator
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     *
     * @return float
     */
    public function calculate($order)
    {
        /** @var \OxidEsales\Eshop\Core\Model\ListModel */
        $orderArticleList = $order->getOrderArticles();
        $orderArticles = array_values($orderArticleList->getArray());

        $weight = 0.0;

        foreach ($orderArticles as &$orderArticle) {
            $weight += floatval($orderArticle->getFieldData('oxweight')) * floatval($orderArticle->getFieldData('oxamount'));
        }

        return $weight;
    }
}
