<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class DeliveryAddressDtoFactory
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     */
    public function create($order): DeliveryAddressDto
    {
        $deliveryAddressDto = new DeliveryAddressDto();

        if ($order->getFieldData("oxdelstreet")) {
            $deliveryAddressDto->addressLine1 = $order->getFieldData("oxdelstreet") . " " . $order->getFieldData("oxdelstreetnr");
        } else {
            $deliveryAddressDto->addressLine1 = $order->getFieldData("oxbillstreet") . " " . $order->getFieldData("oxbillstreetnr");
        }

        $deliveryAddressDto->city = strval($order->getFieldData("oxdelcity") ?: $order->getFieldData("oxbillcity")) ?: null;
        $deliveryAddressDto->company = strval($order->getFieldData("oxdelcompany") ?: $order->getFieldData("oxbillcompany")) ?: null;
        $deliveryAddressDto->firstname = strval($order->getFieldData("oxdelfname") ?: $order->getFieldData("oxbillfname")) ?: null;
        $deliveryAddressDto->lastname = strval($order->getFieldData("oxdellname") ?: $order->getFieldData("oxbilllname")) ?: null;
        $deliveryAddressDto->salutation = strval($order->getFieldData("oxdelsal") ?: $order->getFieldData("oxbillsal")) ?: null;
        $deliveryAddressDto->vatId = strval($order->getFieldData("oxdelustid") ?: $order->getFieldData("oxbillustid")) ?: null;
        $deliveryAddressDto->zipCode = strval($order->getFieldData("oxdelzip") ?: $order->getFieldData("oxbillzip")) ?: null;

        $countryId = $order->getFieldData("oxdelcountryid") ?: $order->getFieldData("oxbillcountryid");
        if ($countryId != "") {
            /** @phpstan-ignore-next-line */
            $countryQueryBuilder = ContainerFactory::getInstance()
                ->getContainer()
                ->get(QueryBuilderFactoryInterface::class)
                ->create();

            $countryQueryBuilder->select('oxcountry.oxisoalpha2')
                ->from('oxcountry')
                ->where('(oxid = :countryid)')
                ->setParameters([
                    ':countryid' => $countryId
                ]);

            /** @phpstan-ignore-next-line */
            $deliveryAddressDto->country = strval($countryQueryBuilder->execute()->fetchOne()) ?: null;
        }

        $stateId = $order->getFieldData("oxdelstateid") ?: $order->getFieldData("oxbillstateid");
        if ($stateId != "") {
            /** @phpstan-ignore-next-line */
            $stateQueryBuilder = ContainerFactory::getInstance()
                ->getContainer()
                ->get(QueryBuilderFactoryInterface::class)
                ->create();

            $stateQueryBuilder->select('oxstates.oxtitle')
                ->from('oxstates')
                ->where('(oxid = :stateid)')
                ->setParameters([
                    ':stateid' => $stateId
                ]);

            /** @phpstan-ignore-next-line */
            $deliveryAddressDto->region = strval($stateQueryBuilder->execute()->fetchOne()) ?: null;
        }

        return $deliveryAddressDto;
    }
}
