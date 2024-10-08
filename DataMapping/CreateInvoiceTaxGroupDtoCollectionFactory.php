<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDtoCollection;

class CreateInvoiceTaxGroupDtoCollectionFactory
{
    /**
     * @var CreateInvoiceTaxGroupDtoFactory
     */
    private $createInvoiceTaxGroupDtoFactory;

    public function __construct(CreateInvoiceTaxGroupDtoFactory $createInvoiceTaxGroupDtoFactory)
    {
        $this->createInvoiceTaxGroupDtoFactory = $createInvoiceTaxGroupDtoFactory;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     *
     * @return CreateInvoiceTaxGroupDtoCollection
     */
    public function create($order)
    {
        /** @var \OxidEsales\Eshop\Core\Model\ListModel */
        $orderArticles = $order->getOrderArticles();

        $positionTaxValues = array_map([$this->createInvoiceTaxGroupDtoFactory, 'create'], $orderArticles->getArray());

        $voucherTaxGroup = $this->createInvoiceTaxGroupDtoFactory->createVoucherPosition($order);
        if (!is_null($voucherTaxGroup)) {
            $positionTaxValues[] = $voucherTaxGroup;
        }

        $positionTaxValues[] = $this->createInvoiceTaxGroupDtoFactory->createShippingPosition($order);

        $taxGroups = array_values(
            array_reduce(
                $positionTaxValues,
                function (array $agg, CreateInvoiceTaxGroupDto $cur) {
                    if (array_key_exists("{$cur->taxPercent}", $agg)) {
                        $agg["{$cur->taxPercent}"]->total += $cur->total;
                        $agg["{$cur->taxPercent}"]->valueToTax += $cur->valueToTax;
                    } else {
                        $agg["{$cur->taxPercent}"] = $cur;
                    }

                    return $agg;
                },
                []
            )
        );

        return new CreateInvoiceTaxGroupDtoCollection(...$taxGroups);
    }
}
