<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class DeliveryAddressDtoFactory
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return \Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto
     */
    public function create($order)
    {
        $deliveryAddressDto = new DeliveryAddressDto();

        if (strval($order->getFieldData("oxdelstreet")) !== '') {
            $deliveryAddressDto->addressLine1 = $order->getFieldData("oxdelstreet") . " " . $order->getFieldData("oxdelstreetnr");
        } else {
            $deliveryAddressDto->addressLine1 = $order->getFieldData("oxbillstreet") . " " . $order->getFieldData("oxbillstreetnr");
        }

        $deliveryAddressDto->city = $this->getStringFieldOrAlternative($order, 'oxdelcity', 'oxbillcity');
        $deliveryAddressDto->company = $this->getStringFieldOrAlternative($order, 'oxdelcompany', 'oxbillcompany');
        $deliveryAddressDto->firstname = $this->getStringFieldOrAlternative($order, 'oxdelfname', 'oxbillfname');
        $deliveryAddressDto->lastname = $this->getStringFieldOrAlternative($order, 'oxdellname', 'oxbilllname');
        $deliveryAddressDto->salutation = $this->getStringFieldOrAlternative($order, 'oxdelsal', 'oxbillsal');
        $deliveryAddressDto->vatId = $this->getStringFieldOrAlternative($order, 'oxdelustid', 'oxbillustid');
        $deliveryAddressDto->zipCode = $this->getStringFieldOrAlternative($order, 'oxdelzip', 'oxbillzip');

        $countryId = $this->getStringFieldOrAlternative($order, 'oxdelcountryid', 'oxbillcountryid');
        if ($countryId !== "") {
            /** @var QueryBuilderFactoryInterface */
            $countryQueryBuilderFactory = ContainerFactory::getInstance()
                ->getContainer()
                ->get(QueryBuilderFactoryInterface::class);

            $countryQueryBuilder = $countryQueryBuilderFactory->create();

            $countryQueryBuilder->select('oxcountry.oxisoalpha2')
                ->from('oxcountry')
                ->where('(oxid = :countryid)')
                ->setParameters([
                    ':countryid' => $countryId
                ]);

            /** @phpstan-ignore-next-line */
            $deliveryAddressDto->country = strval($countryQueryBuilder->execute()->fetchOne()) !== '' ? strval($countryQueryBuilder->execute()->fetchOne()) : null;
        }

        $stateId = $this->getStringFieldOrAlternative($order, 'oxdelstateid', 'oxbillstateid');
        if ($stateId !== "") {
            /** @var QueryBuilderFactoryInterface */
            $stateQueryBuilderFactory = ContainerFactory::getInstance()
                ->getContainer()
                ->get(QueryBuilderFactoryInterface::class);

            $stateQueryBuilder = $stateQueryBuilderFactory->create();

            $stateQueryBuilder->select('oxstates.oxtitle')
                ->from('oxstates')
                ->where('(oxid = :stateid)')
                ->setParameters([
                    ':stateid' => $stateId
                ]);

            /** @phpstan-ignore-next-line */
            $deliveryAddressDto->region = strval($stateQueryBuilder->execute()->fetchOne()) !== '' ? strval($stateQueryBuilder->execute()->fetchOne()) : null;
        }

        return $deliveryAddressDto;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @param string $fieldName
     * @param string $altFieldName
     * @return string|null
     */
    private function getStringFieldOrAlternative($order, $fieldName, $altFieldName)
    {
        $fieldValue = $this->getStringField($order, $fieldName);
        /** @phpstan-ignore-next-line */
        if (empty($fieldValue)) {
            return $this->getStringField($order, $altFieldName);
        }
        return $fieldValue;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @param string $fieldName
     * @return string|null
     */
    private function getStringField($order, $fieldName)
    {
        $fieldValue = $order->getFieldData($fieldName);
        if (!is_null($fieldValue)) {
            return strval($fieldValue);
        }
        return null;
    }
}
