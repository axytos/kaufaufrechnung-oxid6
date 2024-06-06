<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class InvoiceAddressDtoFactory
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @return \Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto
     */
    public function create($order)
    {
        $invoiceAddressDto = new InvoiceAddressDto();

        $invoiceAddressDto->addressLine1 = $order->getFieldData("oxbillstreet") . " " . $order->getFieldData("oxbillstreetnr");
        $invoiceAddressDto->city = strval($order->getFieldData("oxbillcity")) !== '' ? strval($order->getFieldData("oxbillcity")) : null;
        $invoiceAddressDto->company = strval($order->getFieldData("oxbillcompany")) !== '' ? strval($order->getFieldData("oxbillcompany")) : null;
        $invoiceAddressDto->firstname = strval($order->getFieldData("oxbillfname")) !== '' ? strval($order->getFieldData("oxbillfname")) : null;
        $invoiceAddressDto->lastname = strval($order->getFieldData("oxbilllname")) !== '' ? strval($order->getFieldData("oxbilllname")) : null;
        $invoiceAddressDto->salutation = strval($order->getFieldData("oxbillsal")) !== '' ? strval($order->getFieldData("oxbillsal")) : null;
        $invoiceAddressDto->vatId = strval($order->getFieldData("oxbillustid")) !== '' ? strval($order->getFieldData("oxbillustid")) : null;
        $invoiceAddressDto->zipCode = strval($order->getFieldData("oxbillzip")) !== '' ? strval($order->getFieldData("oxbillzip")) : null;

        $countryId = $order->getFieldData("oxbillcountryid");
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
            $invoiceAddressDto->country = strval($countryQueryBuilder->execute()->fetchOne()) !== '' ? strval($countryQueryBuilder->execute()->fetchOne()) : null;
        }

        $stateId = $order->getFieldData("oxbillstateid");
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
            $invoiceAddressDto->region = strval($stateQueryBuilder->execute()->fetchOne()) !== '' ? strval($stateQueryBuilder->execute()->fetchOne()) : null;
        }

        return $invoiceAddressDto;
    }
}
