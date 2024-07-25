<?php

namespace District5\MondocLogHandler\Db;

use DateTime;
use District5\Mondoc\Db\Service\MondocAbstractService;
use District5\Mondoc\Helper\MondocPaginationHelper;
use District5\Mondoc\Helper\MondocTypes;
use District5\MondocBuilder\QueryBuilder;
use District5\MondocLogHandler\MondocLogConfig;
use MongoDB\BSON\ObjectId;
use MongoDB\Exception\UnsupportedException;
use Monolog\LogRecord;
use Throwable;

/**
 * @class MondocLogService
 * @package District5\MondocLogHandler\Db
 * @method static MondocLogModel[] getMultiByCriteria(array $filter = [], array $options = [])
 * @method static MondocLogModel[] getMultiByQueryBuilder(QueryBuilder $builder)
 * @method static MondocLogModel[] getByIds(array $ids)
 * @method static MondocLogModel[] getMultiWhereKeyEqualsValue(string $key, $value)
 * @method static MondocLogModel[] getMultiWhereKeyDoesNotEqualValue(string $key, $value)
 * @method static MondocLogModel|null getOneByCriteria(array $filter = [], array $options = [])
 * @method static MondocLogModel|null getById(ObjectId|string $id)
 * @method static MondocLogModel|null getOneByQueryBuilder(QueryBuilder $builder)
 * @method static MondocLogModel|null getOneWhereKeyEqualsValue(string $key, mixed $value)
 * @method static MondocLogModel|null getOneWhereKeyDoesNotEqualValue(string $key, mixed $value)
 * @method static MondocLogModel[] getPage(MondocPaginationHelper $paginator, array $filter = [], ?string $sortByField = null, int $sortDirection = -1)
 * @method static MondocLogModel[] getPageByByObjectIdPagination(MondocPaginationHelper $paginator, ObjectId|string|null $currentId, int $sortDirection = -1, array $filter = [])
 */
class MondocLogService extends MondocAbstractService
{
    /**
     * @param DateTime $start
     * @param DateTime $end
     * @param int $currentPage
     * @param int $perPage
     * @param array $additionalQuery
     * @return MondocPaginationHelper
     */
    public static function getPaginatorBetweenDates(DateTime $start, DateTime $end, int $currentPage, int $perPage = 1000, array $additionalQuery = []): MondocPaginationHelper
    {
        $query = array_merge($additionalQuery, [
            'cd' => [
                '$gte' => MondocTypes::phpDateToMongoDateTime($start),
                '$lte' => MondocTypes::phpDateToMongoDateTime($end)
            ]
        ]);

        return new MondocPaginationHelper(
            self::countAll($query),
            $currentPage,
            $perPage
        );
    }

    /**
     * @return MondocLogModel[]
     */
    public static function getPaginatedLogsBetweenDates(MondocPaginationHelper $paginator, DateTime $start, DateTime $end, array $additionalQuery = [], $sortDirection = -1): array
    {
        if ($sortDirection === 1) {
            $sort = ['_id' => 1];
        } else {
            $sort = ['_id' => -1];
        }

        $query = array_merge($additionalQuery, [
            'cd' => [
                '$gte' => MondocTypes::phpDateToMongoDateTime($start),
                '$lte' => MondocTypes::phpDateToMongoDateTime($end)
            ]
        ]);

        return self::getMultiByCriteria(
            $query,
            [
                'limit' => $paginator->getLimit(),
                'skip' => $paginator->getSkip(),
                'sort' => $sort
            ]
        );
    }

    /**
     * @param LogRecord $record
     * @return MondocLogModel|null
     */
    public static function createFromLogRecord(LogRecord $record): MondocLogModel|null
    {
        $date = DateTime::createFromImmutable($record->datetime);
        $model = new MondocLogModel();
        $model->setChannel($record->channel);
        $model->setLevel($record->level);
        $model->setMessage($record->formatted);
        $model->setCreatedDate($date);
        $model->setExtra($record->extra);
        $model->setContext($record->context);
        if ($model->save()) {
            return $model;
        }

        return null;
    }

    /**
     * Create a single index for the collection.
     */
    public static function createIndex(): void
    {
        $collection = self::getCollection();
        $collection->createIndex(['cd' => 1]);
        $collection->createIndex(['channel' => 1]);
        $collection->createIndex(['level' => 1]);
    }

    /**
     * @return string
     * @see MondocLogConfig::getCollectionName()
     * @see MondocAbstractService::getCollectionName()
     */
    protected static function getCollectionName(): string
    {
        return MondocLogConfig::getInstance()->getCollectionName();
    }

    /**
     * @return string
     * @see MondocLogConfig::getConnectionId()
     * @see MondocAbstractService::getConnectionId()
     */
    protected static function getConnectionId(): string
    {
        return MondocLogConfig::getInstance()->getConnectionId();
    }
}
