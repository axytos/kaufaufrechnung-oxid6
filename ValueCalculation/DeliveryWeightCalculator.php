<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\ValueCalculation;

use OxidEsales\Eshop\Core\Model\ListModel;
use OxidEsales\Eshop\Application\Model\Order;

class DeliveryWeightCalculator
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     */
    public function calculate($order): float
    {
        /** @var ListModel */
        $orderArticleList = $order->getOrderArticles();
        $orderArticles = array_values($orderArticleList->getArray());

        $weight = 0.0;

        foreach ($orderArticles as &$orderArticle) {
            $weight += floatval($orderArticle->getFieldData("oxweight")) * floatval($orderArticle->getFieldData("oxamount"));
        }
        return $weight;
    }
}
