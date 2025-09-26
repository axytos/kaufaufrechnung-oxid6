<?php

/**
 * Metadata version.
 */

use Axytos\KaufAufRechnung_OXID6\Controller\ActionCallbackController;
use Axytos\KaufAufRechnung_OXID6\Controller\CreditCheckAgreementController;
use Axytos\KaufAufRechnung_OXID6\Events\AxytosEvents;
use Axytos\KaufAufRechnung_OXID6\Extend\Model\AxytosMaintenance;
use Axytos\KaufAufRechnung_OXID6\Extend\Model\AxytosOrder;
use Axytos\KaufAufRechnung_OXID6\Extend\Model\AxytosPayment;
use Axytos\KaufAufRechnung_OXID6\Extend\Model\AxytosPaymentGateway;
use Axytos\KaufAufRechnung_OXID6\Extend\Model\AxytosPaymentList;
use OxidEsales\Eshop\Application\Model\Maintenance;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Application\Model\PaymentGateway;
use OxidEsales\Eshop\Application\Model\PaymentList;

$sMetadataVersion = '2.0';

/**
 * Module information.
 */
$aModule = [
    'id' => 'axytos_kaufaufrechnung',
    'title' => [
        'de' => 'Kauf auf Rechnung',
        'en' => 'Buy Now Pay Later',
        'fr' => 'Buy Now Pay Later',
        'es' => 'Buy Now Pay Later',
        'nl' => 'Buy Now Pay Later',
    ],
    'description' => [
        'de' => 'Sie zahlen bequem die Rechnung, sobald Sie die Ware erhalten haben, innerhalb der Zahlfrist',
        'en' => 'You conveniently pay the invoice as soon as you receive the goods, within the payment period',
        'fr' => 'Vous payez la facture dès que vous recevez la marchandise, dans le délai de paiement.',
        'es' => 'Pagas la factura convenientemente en cuanto has recibido la mercancía, dentro del plazo de pago.',
        'nl' => 'Je moet de factuur betalen zodra je de goederen hebt ontvangen, binnen de betalingstermijn.',
    ],
    'thumbnail' => 'assets/img/logo.png',
    'version' => '1.8.0-alpha',
    'author' => 'axytos GmbH',
    'url' => 'https://www.axytos.com',
    'email' => 'info@axytos.com',
    'extend' => [
        PaymentList::class => AxytosPaymentList::class,
        PaymentGateway::class => AxytosPaymentGateway::class,
        Payment::class => AxytosPayment::class,
        Maintenance::class => AxytosMaintenance::class,
        Order::class => AxytosOrder::class,
    ],
    'events' => [
        'onActivate' => AxytosEvents::class . '::onActivate',
        'onDeactivate' => AxytosEvents::class . '::onDeactivate',
    ],
    'controllers' => [
        'axytos_kaufaufrechnung_credit_check_agreement' => CreditCheckAgreementController::class,
        'axytos_kaufaufrechnung_action_callback' => ActionCallbackController::class,
    ],
    'settings' => [
        [
            'group' => 'axytos_kaufaufrechnung_settings',
            'name' => 'axytos_kaufaufrechnung_api_host',
            'type' => 'select',
            'value' => 'APIHOST_SANDBOX',
            'constraints' => 'APIHOST_LIVE|APIHOST_SANDBOX',
        ],
        [
            'group' => 'axytos_kaufaufrechnung_settings',
            'name' => 'axytos_kaufaufrechnung_api_key',
            'type' => 'password',
            'value' => '',
        ],
        [
            'group' => 'axytos_kaufaufrechnung_settings',
            'name' => 'axytos_kaufaufrechnung_client_secret',
            'type' => 'password',
            'value' => '',
        ],
        [
            'group' => 'axytos_kaufaufrechnung_settings',
            'name' => 'axytos_kaufaufrechnung_error_message',
            'type' => 'str',
            'value' => '',
        ],
    ],
    'templates' => [
        'credit_check_agreement.tpl' => 'axytos/kaufaufrechnung/views/tpl/credit_check_agreement.tpl',
    ],
    'blocks' => [
        [
            'template' => 'page/checkout/payment.tpl',
            'block' => 'change_payment',
            'file' => 'views/blocks/axytos_kaufaufrechnung_change_payment.tpl',
        ],
        [
            'template' => 'page/checkout/payment.tpl',
            'block' => 'select_payment',
            'file' => 'views/blocks/axytos_kaufaufrechnung_select_payment.tpl',
        ],
        [
            'template' => 'page/checkout/payment.tpl',
            'block' => 'checkout_payment_nextstep',
            'file' => 'views/blocks/axytos_kaufaufrechnung_checkout_payment_nextstep.tpl',
        ],
    ],
];
