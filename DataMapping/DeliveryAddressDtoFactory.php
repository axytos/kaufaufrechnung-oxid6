<?php

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\KaufAufRechnung_OXID6\DataAbstractionLayer\OrderRepository;

class DeliveryAddressDtoFactory
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @return void
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     *
     * @return DeliveryAddressDto
     */
    public function create($order)
    {
        $deliveryAddressDto = new DeliveryAddressDto();

        if ('' !== strval($order->getFieldData('oxdelstreet'))) {
            $deliveryAddressDto->addressLine1 = $order->getFieldData('oxdelstreet') . ' ' . $order->getFieldData('oxdelstreetnr');
        } else {
            $deliveryAddressDto->addressLine1 = $order->getFieldData('oxbillstreet') . ' ' . $order->getFieldData('oxbillstreetnr');
        }

        $deliveryAddressDto->city = $this->getStringFieldOrAlternative($order, 'oxdelcity', 'oxbillcity');
        $deliveryAddressDto->company = $this->getStringFieldOrAlternative($order, 'oxdelcompany', 'oxbillcompany');
        $deliveryAddressDto->firstname = $this->getStringFieldOrAlternative($order, 'oxdelfname', 'oxbillfname');
        $deliveryAddressDto->lastname = $this->getStringFieldOrAlternative($order, 'oxdellname', 'oxbilllname');
        $deliveryAddressDto->salutation = $this->getStringFieldOrAlternative($order, 'oxdelsal', 'oxbillsal');
        $deliveryAddressDto->vatId = $this->getStringFieldOrAlternative($order, 'oxdelustid', 'oxbillustid');
        $deliveryAddressDto->zipCode = $this->getStringFieldOrAlternative($order, 'oxdelzip', 'oxbillzip');

        $countryId = $this->getStringFieldOrAlternative($order, 'oxdelcountryid', 'oxbillcountryid');
        if ('' !== $countryId) {
            $deliveryAddressDto->country = $this->orderRepository->findDeliveryAddressCountryById($countryId);
        }

        $stateId = $this->getStringFieldOrAlternative($order, 'oxdelstateid', 'oxbillstateid');
        if ('' !== $stateId) {
            $deliveryAddressDto->region = $this->orderRepository->findDeliveryAddressStateById($stateId);
        }

        return $deliveryAddressDto;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @param string                                    $fieldName
     * @param string                                    $altFieldName
     *
     * @return string|null
     */
    private function getStringFieldOrAlternative($order, $fieldName, $altFieldName)
    {
        $fieldValue = $this->getStringField($order, $fieldName);

        // some minor version of OXID allow columns not to be null, e.g. OXDELCOUNTRYID
        // so we need to also check for empty values
        if (is_null($fieldValue) || '' === trim($fieldValue)) {
            return $this->getStringField($order, $altFieldName);
        }

        return $fieldValue;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     * @param string                                    $fieldName
     *
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
