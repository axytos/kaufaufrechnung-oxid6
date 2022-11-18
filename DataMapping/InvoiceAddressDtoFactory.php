<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class InvoiceAddressDtoFactory
{
    public function create(Order $order): InvoiceAddressDto
    {
        $invoiceAddressDto = new InvoiceAddressDto();

        $invoiceAddressDto->addressLine1 = $order->getFieldData("oxbillstreet") . " " . $order->getFieldData("oxbillstreetnr");
        $invoiceAddressDto->city = strval($order->getFieldData("oxbillcity")) ?: null;
        $invoiceAddressDto->company = strval($order->getFieldData("oxbillcompany")) ?: null;
        $invoiceAddressDto->firstname = strval($order->getFieldData("oxbillfname")) ?: null;
        $invoiceAddressDto->lastname = strval($order->getFieldData("oxbilllname")) ?: null;
        $invoiceAddressDto->salutation = strval($order->getFieldData("oxbillsal")) ?: null;
        $invoiceAddressDto->vatId = strval($order->getFieldData("oxbillustid")) ?: null;
        $invoiceAddressDto->zipCode = strval($order->getFieldData("oxbillzip")) ?: null;

        $countryId = $order->getFieldData("oxbillcountryid");
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
            $invoiceAddressDto->country = strval($countryQueryBuilder->execute()->fetchOne()) ?: null;
        }

        $stateId = $order->getFieldData("oxbillstateid");
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
            $invoiceAddressDto->region = strval($stateQueryBuilder->execute()->fetchOne()) ?: null;
        }

        return $invoiceAddressDto;
    }
}
