<?php

namespace Axytos\KaufAufRechnung_OXID6\DataAbstractionLayer;

use Axytos\ECommerce\Order\OrderCheckProcessStates;
use Axytos\KaufAufRechnung\Core\Model\OrderStateMachine\OrderStates;
use Axytos\KaufAufRechnung_OXID6\DependencyInjection\ContainerFactory;
use Axytos\KaufAufRechnung_OXID6\Events\AxytosEvents;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class OrderRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return void
     */
    public function startTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * @return void
     */
    public function commitTransaction()
    {
        /**
         * @var \Doctrine\DBAL\Query\QueryBuilder
         *
         * @phpstan-ignore-next-line
         */
        $queryBuilder = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
            ->create()
        ;

        $this->connection->commit();
    }

    /**
     * @return void
     */
    public function rollbackTransaction()
    {
        /**
         * @var \Doctrine\DBAL\Query\QueryBuilder
         *
         * @phpstan-ignore-next-line
         */
        $queryBuilder = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
            ->create()
            ->executeStatement()
        ;

        $this->connection->rollBack();
    }

    /**
     * @param string $orderId
     *
     * @return \OxidEsales\Eshop\Application\Model\Order|null
     */
    public function findOrder($orderId)
    {
        /** @var \OxidEsales\Eshop\Application\Model\Order */
        $order = oxNew('oxorder');
        if ($order->load($orderId)) {
            return $order;
        }

        return null;
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     *
     * @return string
     */
    public function findLogistician($order)
    {
        /**
         * @var \Doctrine\DBAL\Query\QueryBuilder
         *
         * @phpstan-ignore-next-line
         */
        $queryBuilder = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
            ->create()
        ;

        $queryBuilder->select('oxdeliveryset.oxtitle')
            ->from('oxdeliveryset')
            ->where('oxid = :oxdeliveryid')
            ->setParameters([
                ':oxdeliveryid' => $order->getFieldData('oxdeltype'),
            ])
        ;

        /** @var \Doctrine\DBAL\Result */
        $result = $queryBuilder->executeQuery();
        $value = strval($result->fetchOne());

        return '' !== $value ? $value : '';
    }

    /**
     * @param mixed $countryId
     *
     * @return string|null
     */
    public function findDeliveryAddressCountryById($countryId)
    {
        /** @var QueryBuilderFactoryInterface */
        $countryQueryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
        ;

        $countryQueryBuilder = $countryQueryBuilderFactory->create();

        $countryQueryBuilder->select('oxcountry.oxisoalpha2')
            ->from('oxcountry')
            ->where('(oxid = :countryid)')
            ->setParameters([
                ':countryid' => $countryId,
            ])
        ;

        /** @phpstan-ignore-next-line */
        $country = strval($countryQueryBuilder->executeQuery()->fetchOne());

        return '' !== $country ? $country : null;
    }

    /**
     * @param mixed $stateId
     *
     * @return string|null
     */
    public function findDeliveryAddressStateById($stateId)
    {
        /** @var QueryBuilderFactoryInterface */
        $stateQueryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
        ;

        $stateQueryBuilder = $stateQueryBuilderFactory->create();

        $stateQueryBuilder->select('oxstates.oxtitle')
            ->from('oxstates')
            ->where('(oxid = :stateid)')
            ->setParameters([
                ':stateid' => $stateId,
            ])
        ;

        /** @phpstan-ignore-next-line */
        $state = strval($stateQueryBuilder->executeQuery()->fetchOne());

        return '' !== $state ? $state : null;
    }

    /**
     * @param mixed $countryId
     *
     * @return string|null
     */
    public function findInvoiceAddressCountryById($countryId)
    {
        /** @var QueryBuilderFactoryInterface */
        $countryQueryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
        ;

        $countryQueryBuilder = $countryQueryBuilderFactory->create();

        $countryQueryBuilder->select('oxcountry.oxisoalpha2')
            ->from('oxcountry')
            ->where('(oxid = :countryid)')
            ->setParameters([
                ':countryid' => $countryId,
            ])
        ;

        /** @phpstan-ignore-next-line */
        $country = strval($countryQueryBuilder->executeQuery()->fetchOne());

        return '' !== $country ? $country : null;
    }

    /**
     * @param mixed $stateId
     *
     * @return string|null
     */
    public function findInvoiceAddressStateById($stateId)
    {
        /** @var QueryBuilderFactoryInterface */
        $stateQueryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
        ;

        $stateQueryBuilder = $stateQueryBuilderFactory->create();

        $stateQueryBuilder->select('oxstates.oxtitle')
            ->from('oxstates')
            ->where('(oxid = :stateid)')
            ->setParameters([
                ':stateid' => $stateId,
            ])
        ;

        /** @phpstan-ignore-next-line */
        $state = strval($stateQueryBuilder->executeQuery()->fetchOne());

        return '' !== $state ? $state : null;
    }

    /**
     * @param string[]    $orderStates
     * @param int|null    $limit
     * @param string|null $startId
     *
     * @return \OxidEsales\Eshop\Application\Model\Order[]
     */
    public function getOrdersByStates($orderStates, $limit = null, $startId = null)
    {
        if (0 === count($orderStates)) {
            return [];
        }

        $orderStates = array_values($orderStates);

        $parameters = [
            ':oxpaymenttype' => AxytosEvents::PAYMENT_METHOD_ID,
        ];

        $orderStateParameterNames = [];

        for ($i = 0; $i < count($orderStates); ++$i) {
            $name = ":orderState{$i}";
            $parameters[$name] = $orderStates[$i];
            array_push($orderStateParameterNames, ":orderState{$i}");
        }

        /** @var QueryBuilderFactoryInterface */
        $queryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
        ;

        $queryBuilder = $queryBuilderFactory->create();

        $queryBuilder->select('oxorder.oxid')
            ->from('oxorder')
            ->where('oxpaymenttype = :oxpaymenttype')
            ->andWhere($queryBuilder->expr()->or(
                $queryBuilder->expr()->in('axytoskaufaufrechnungorderstate', $orderStateParameterNames),
                $queryBuilder->expr()->isNull('axytoskaufaufrechnungorderstate')
            ))
            ->orderBy('oxordernr', 'ASC')
            ->setParameters($parameters)
        ;

        if (is_int($limit)) {
            $queryBuilder->setMaxResults($limit);
        }

        if (is_string($startId)) {
            $queryBuilder
                ->andWhere('oxordernr >= :startId')
                ->setParameter(':startId', $startId, ParameterType::STRING)
            ;
        }

        /** @var \Doctrine\DBAL\Result */
        $result = $queryBuilder->executeQuery();
        /** @var array<array<string,mixed>> */
        $rows = $result->fetchAllAssociative();

        /** @var \Axytos\KaufAufRechnung\Core\Plugin\Abstractions\Logging\LoggerAdapterInterface */
        $logger = ContainerFactory::getInstance()
            ->getContainer()
            ->get(\Axytos\KaufAufRechnung\Core\Plugin\Abstractions\Logging\LoggerAdapterInterface::class)
        ;

        $logger->info('Found orders: ' . count($rows));

        /** @var array<string> */
        $orderIds = array_map(function ($row) {
            return $row['oxid'];
        }, $rows);

        $orders = array_map([$this, 'findOrder'], $orderIds);

        return array_filter($orders);
    }

    /**
     * @param string|int $orderNumber
     *
     * @return \OxidEsales\Eshop\Application\Model\Order|null
     */
    public function getOrderByOrderNumber($orderNumber)
    {
        $orderNumber = intval($orderNumber);

        /** @var QueryBuilderFactoryInterface */
        $queryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
        ;

        $queryBuilder = $queryBuilderFactory->create();

        $queryBuilder->select('oxorder.oxid')
            ->from('oxorder')
            ->where('oxpaymenttype = :oxpaymenttype')
            ->andWhere('oxordernr = :orderNumber')
            ->setParameters([
                ':oxpaymenttype' => AxytosEvents::PAYMENT_METHOD_ID,
                ':orderNumber' => $orderNumber,
            ])
        ;

        /** @var \Doctrine\DBAL\Result */
        $result = $queryBuilder->executeQuery();
        $oxid = strval($result->fetchOne());

        return $this->findOrder($oxid);
    }

    /**
     * @return void
     */
    public function migrateOrderStates()
    {
        /** @var QueryBuilderFactoryInterface */
        $queryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
        ;
        $queryBuilder = $queryBuilderFactory->create();

        // SQL to check if column exists
        $checkColumnSql = 'SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?';

        $tableName = 'oxorder';
        $orderCheckProcessStatusColumnName = 'axytoskaufaufrechnungordercheckprocessstatus';
        $orderStateColumnName = 'axytoskaufaufrechnungorderstate';

        $checkProcessStatusColumnExists = intval($this->connection->fetchOne($checkColumnSql, [$tableName, $orderCheckProcessStatusColumnName]));
        $orderStateColumnExists = intval($this->connection->fetchOne($checkColumnSql, [$tableName, $orderStateColumnName]));

        if (1 === $checkProcessStatusColumnExists && 1 === $orderStateColumnExists) {
            $queryBuilder->select('oxorder.oxid')
                ->from('oxorder')
                ->where('oxpaymenttype = :oxpaymenttype')
                ->andWhere($queryBuilder->expr()->isNull('axytoskaufaufrechnungorderstate'))
                ->setParameters([
                    ':oxpaymenttype' => AxytosEvents::PAYMENT_METHOD_ID,
                ])
            ;

            /** @var \Doctrine\DBAL\Result */
            $result = $queryBuilder->executeQuery();
            /** @var array<array<string,mixed>> */
            $rows = $result->fetchAllAssociative();

            /** @var array<string> */
            $orderIds = array_map(function ($row) {
                return $row['oxid'];
            }, $rows);

            foreach ($orderIds as $orderId) {
                /** @var \OxidEsales\Eshop\Application\Model\Order */
                $order = $this->findOrder($orderId);
                $orderState = $this->mapAttributesToOrderState($order);
                // do not overwrite existing values with null
                if (!is_null($orderState)) {
                    /** @phpstan-ignore-next-line */
                    $order->oxorder__axytoskaufaufrechnungorderstate = new Field($orderState);
                    $order->save();
                }
            }
        }
    }

    /**
     * @param \OxidEsales\Eshop\Application\Model\Order|null $order
     *
     * @return string|null
     */
    private function mapAttributesToOrderState($order)
    {
        if (is_null($order)) {
            return null;
        }

        $checkProcessState = strval($order->getFieldData('axytoskaufaufrechnungordercheckprocessstatus'));
        $hasCancelReported = boolval($order->getFieldData('oxstorno'));
        $hasCreateInvoiceReported = '' !== strval($order->getFieldData('oxbillnr'));
        $hasRefundReported = false; // refund reports are currently not a supported feature for oxid
        $hasPaymentReproted = false; // payment reports are currently not a supported feature for oxid

        switch ($checkProcessState) {
            case OrderCheckProcessStates::CHECKED:
            case OrderCheckProcessStates::FAILED:
                return OrderStates::CHECKOUT_FAILED;
            case OrderCheckProcessStates::CONFIRMED:
                if ($hasPaymentReproted) {  /** @phpstan-ignore-line */
                    return OrderStates::COMPLETELY_PAID;
                }
                if ($hasRefundReported) {  /** @phpstan-ignore-line */
                    return OrderStates::COMPLETELY_REFUNDED;
                }
                if ($hasCreateInvoiceReported) {
                    return OrderStates::INVOICED;
                }
                if ($hasCancelReported) {
                    return OrderStates::CANCELED;
                }

                return OrderStates::CHECKOUT_CONFIRMED;

            case OrderCheckProcessStates::UNCHECKED:
            default:
                return null;
        }
    }

    /**
     * @return void
     */
    public static function createOrderColumns()
    {
        self::addOrderPreCheckResult();
        self::addShippingReported();
        self::addReportedTrackingCode();
        self::addOrderBasketHash();
        self::addOrderState();
        self::addOrderStateData();
    }

    /**
     * @return void
     */
    private static function addOrderPreCheckResult()
    {
        self::addTableColumn(
            'oxorder',
            'AXYTOSKAUFAUFRECHNUNGORDERPRECHECKRESULT',
            'TEXT'
        );
    }

    /**
     * @return void
     */
    private static function addShippingReported()
    {
        self::addTableColumn(
            'oxorder',
            'AXYTOSKAUFAUFRECHNUNGSHIPPINGREPORTED',
            'TINYINT(1) NOT NULL DEFAULT 0'
        );
    }

    /**
     * @return void
     */
    private static function addReportedTrackingCode()
    {
        self::addTableColumn(
            'oxorder',
            'AXYTOSKAUFAUFRECHNUNGREPORTEDTRACKINGCODE',
            "VARCHAR(128) NOT NULL DEFAULT ''"
        );
    }

    /**
     * @return void
     */
    private static function addOrderBasketHash()
    {
        self::addTableColumn(
            'oxorder',
            'AXYTOSKAUFAUFRECHNUNGORDERBASKETHASH',
            "VARCHAR(64) NOT NULL DEFAULT ''" // possible hash sha256 with 64 chars, but not sha512!
        );
    }

    /**
     * @return void
     */
    private static function addOrderState()
    {
        self::addTableColumn(
            'oxorder',
            'AXYTOSKAUFAUFRECHNUNGORDERSTATE',
            'TEXT'
        );
    }

    /**
     * @return void
     */
    private static function addOrderStateData()
    {
        self::addTableColumn(
            'oxorder',
            'AXYTOSKAUFAUFRECHNUNGORDERSTATEDATA',
            'TEXT'
        );
    }

    /**
     * @param string $tableName
     * @param string $columnName
     * @param string $definition
     *
     * @return void
     */
    private static function addTableColumn($tableName, $columnName, $definition)
    {
        $container = ContainerFactory::getInstance()->getContainer();
        /** @var Connection */
        $connection = $container->get(Connection::class);
        /** @var QueryBuilderFactoryInterface */
        $queryBuilderFactory = $container->get(QueryBuilderFactoryInterface::class);
        $queryBuilder = $queryBuilderFactory->create();

        // SQL to check if column exists
        $checkColumnSql = 'SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?';
        $columnExists = intval($connection->fetchOne($checkColumnSql, [$tableName, $columnName]));

        // If column doesn't exist
        if (0 === $columnExists) {
            $sql = 'ALTER TABLE ' . $tableName . ' ADD COLUMN ' . $columnName . ' ' . $definition;
            $connection->executeStatement($sql);
        }
    }
}
