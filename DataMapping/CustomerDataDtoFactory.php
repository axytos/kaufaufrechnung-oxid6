<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\DataMapping;

use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use DateTimeImmutable;
use OxidEsales\Eshop\Application\Model\Order;

class CustomerDataDtoFactory
{
    public function create(Order $order): CustomerDataDto
    {
        $user = $order->getOrderUser();

        $personalDataDto = new CustomerDataDto();
        $personalDataDto->externalCustomerId = $user->getId();
        if ($user->getFieldData("oxbirthdate") !== "0000-00-00") {
            /** @phpstan-ignore-next-line */
            $personalDataDto->dateOfBirth = DateTimeImmutable::createFromFormat('Y-m-d G:i:s', $user->getFieldData("oxbirthdate") . " 00:00:00");
        }
        /** @phpstan-ignore-next-line */
        $personalDataDto->email = $order->getFieldData("oxbillemail");

        return $personalDataDto;
    }
}
