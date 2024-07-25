<?php

use District5\Mondoc\MondocConfig;
use District5\MondocLogHandler\Db\MondocLogModel;
use District5\MondocLogHandler\Db\MondocLogService;
use MongoDB\Client;

require __DIR__ . '/../vendor/autoload.php';

$connection = new Client(getenv('MONGO_CONNECTION_STRING'));
$mondoc = MondocConfig::getInstance();
/** @noinspection PhpRedundantOptionalArgumentInspection */
$mondoc->addDatabase(
    $connection->selectDatabase(getenv('MONGO_DATABASE') . php_uname('s')),
    'default'
);
$db = $mondoc->getDatabase();
$mondoc->setServiceMap([
    MondocLogModel::class => MondocLogModel::class
]);
MondocLogService::getCollection()->drop();
