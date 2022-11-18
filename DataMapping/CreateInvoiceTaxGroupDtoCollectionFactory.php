<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDtoCollection;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Model\ListModel;

class CreateInvoiceTaxGroupDtoCollectionFactory
{
    private CreateInvoiceTaxGroupDtoFactory $createInvoiceTaxGroupDtoFactory;

    public function __construct(CreateInvoiceTaxGroupDtoFactory $createInvoiceTaxGroupDtoFactory)
    {
        $this->createInvoiceTaxGroupDtoFactory = $createInvoiceTaxGroupDtoFactory;
    }

    public function create(Order $order): CreateInvoiceTaxGroupDtoCollection
    {
        /** @var ListModel */
        $orderArticles = $order->getOrderArticles();
        $positionTaxValues = array_map([$this->createInvoiceTaxGroupDtoFactory, 'create'], $orderArticles->getArray());
        $positionTaxValues[] = $this->createInvoiceTaxGroupDtoFactory->createShippingPosition($order);

        $taxGroups = array_values(
            array_reduce(
                $positionTaxValues,
                function (array $agg, CreateInvoiceTaxGroupDto $cur) {
                    if (array_key_exists("$cur->taxPercent", $agg)) {
                        $agg["$cur->taxPercent"]->total += $cur->total;
                        $agg["$cur->taxPercent"]->valueToTax += $cur->valueToTax;
                    } else {
                        $agg["$cur->taxPercent"] = $cur;
                    }
                    return $agg;
                },
                []
            )
        );
        return new CreateInvoiceTaxGroupDtoCollection(...$taxGroups);
    }
}
