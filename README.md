District5 - Mondoc log handler for Monolog
====

This library is a Monolog handler that logs to a MongoDB collection using the
[Mondoc](https://github.com/district-5/php-mondoc) library. It complements the Monolog library by providing a handler
that logs to a MongoDB collection using Mondoc.

### Installation...

```
composer require district5/mondoc-log-handler
```

### Usage...

```php
<?php
use District5\MondocLogHandler\Handler\MondocLogHandler;
use District5\MondocLogHandler\MondocLogConfig;
use Monolog\Level;
use Monolog\Logger;

// Set up MondocConfig
$client = new \MongoDB\Client('mongodb://localhost:27017');
\District5\Mondoc\MondocConfig::getInstance()->addDatabase(
    $client->selectDatabase('my_database'),
    'default' // This is the default connection id
)

// Set up MondocLogConfig
$config = MondocLogConfig::getInstance()->setConnectionId(
    'default' // This is the default connection id
)->setCollectionName(
    'mondoc_log' // This is the default collection name
);

// Set up the logger
$logger = new Logger('my_app');

$handler = new MondocLogHandler(
    $level = Level::Debug,
    $bubble = true
);
$handler->setFormatter(
    new LineFormatter('%message%') // This is the default formatter and format
);
$logger->pushHandler($handler);
$logger->info('A test log from MondocLogHandler');
$lastLogModel = $handler->getLastLog();

echo $lastLogModel->getLogMessage(); // 'A test log from MondocLogHandler'

```

### Testing...

```
composer install
./vendor/bin/phpunit
```
