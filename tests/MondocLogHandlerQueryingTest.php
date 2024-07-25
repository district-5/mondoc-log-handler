<?php
/**
 * District5 - MondocLogHandler
 *
 * @copyright District5
 *
 * @author District5
 * @link https://www.district5.co.uk
 *
 * @license This software and associated documentation (the "Software") may not be
 * used, copied, modified, distributed, published or licensed to any 3rd party
 * without the written permission of District5 or its author.
 *
 * The above copyright notice and this permission notice shall be included in
 * all licensed copies of the Software.
 *
 */

namespace District5Tests\MondocLogHandlerTests;


use District5\Date\Date;
use District5\Mondoc\Helper\MondocPaginationHelper;
use District5\MondocLogHandler\Db\MondocLogService;
use District5\MondocLogHandler\Handler\MondocLogHandler;
use Monolog\Logger;

/**
 * Class MondocLogHandlerQueryingTest
 * @package District5Tests\MondocLogHandlerTests
 */
class MondocLogHandlerQueryingTest extends TestAbstract
{
    public function testQueryingDescending()
    {
        $this->setupConfig();
        MondocLogService::createIndex();
        MondocLogService::createIndex();

        $logger = new Logger('my_logger');
        $logger->pushHandler(
            $handler = new MondocLogHandler()
        );
        $logger->info('My logger is now ready');
        $lastLog = $handler->getLastLog();
        $this->assertEquals('My logger is now ready', $lastLog->getMessage());
        $logger->info('My logger is now ready 2');
        $lastLogTwo = $handler->getLastLog();
        $this->assertEquals('My logger is now ready 2', $lastLogTwo->getMessage());

        $this->assertEquals(2, MondocLogService::countAll());
        $paginator = MondocLogService::getPaginatorBetweenDates(
            Date::modify()->minus()->days(1),
            Date::modify()->plus()->days(1),
            1,
            1
        );
        $this->assertEquals(2, $paginator->getTotalResults());
        $this->assertEquals(2, $paginator->getTotalPages());

        $firstPage = MondocLogService::getPaginatedLogsBetweenDates(
            $paginator,
            Date::modify()->minus()->days(1),
            Date::modify()->plus()->days(1),
            [],
            -1
        );
        $this->assertEquals('My logger is now ready 2', $firstPage[0]->getMessage());
        $paginator = new MondocPaginationHelper(
            $paginator->getTotalResults(),
            2,
            1
        );
        $secondPage = MondocLogService::getPaginatedLogsBetweenDates(
            $paginator,
            Date::modify()->minus()->days(1),
            Date::modify()->plus()->days(1),
            [],
            -1
        );
        $this->assertEquals('My logger is now ready', $secondPage[0]->getMessage());

        $lastLog->delete();
        $lastLogTwo->delete();
    }

    public function testQueryingAscending()
    {
        $this->setupConfig();

        $logger = new Logger('my_logger');
        $logger->pushHandler(
            $handler = new MondocLogHandler()
        );
        $logger->info('My logger is now ready');
        $lastLog = $handler->getLastLog();
        $this->assertEquals('My logger is now ready', $lastLog->getMessage());
        $logger->info('My logger is now ready 2');
        $lastLogTwo = $handler->getLastLog();
        $this->assertEquals('My logger is now ready 2', $lastLogTwo->getMessage());

        $this->assertEquals(2, MondocLogService::countAll());
        $paginator = MondocLogService::getPaginatorBetweenDates(
            Date::modify()->minus()->days(1),
            Date::modify()->plus()->days(1),
            1,
            1
        );
        $this->assertEquals(2, $paginator->getTotalResults());
        $this->assertEquals(2, $paginator->getTotalPages());

        $firstPage = MondocLogService::getPaginatedLogsBetweenDates(
            $paginator,
            Date::modify()->minus()->days(1),
            Date::modify()->plus()->days(1),
            [],
            1
        );
        $this->assertEquals('My logger is now ready', $firstPage[0]->getMessage());
        $paginator = new MondocPaginationHelper(
            $paginator->getTotalResults(),
            2,
            1
        );
        $secondPage = MondocLogService::getPaginatedLogsBetweenDates(
            $paginator,
            Date::modify()->minus()->days(1),
            Date::modify()->plus()->days(1),
            [],
            1
        );
        $this->assertEquals('My logger is now ready 2', $secondPage[0]->getMessage());

        $lastLog->delete();
        $lastLogTwo->delete();
    }

    public function testQueryingWithFilter()
    {
        $this->setupConfig();

        $logger = new Logger('my_logger');
        $logger->pushHandler(
            $handler = new MondocLogHandler()
        );
        $logger->info('My logger is now ready');
        $lastLog = $handler->getLastLog();
        $this->assertEquals('My logger is now ready', $lastLog->getMessage());
        $logger->info('My logger is now ready 2');
        $lastLogTwo = $handler->getLastLog();
        $this->assertEquals('My logger is now ready 2', $lastLogTwo->getMessage());

        $this->assertEquals(2, MondocLogService::countAll());
        $paginator = MondocLogService::getPaginatorBetweenDates(
            Date::modify()->minus()->days(1),
            Date::modify()->plus()->days(1),
            1,
            100,
            [
                'm' => 'My logger is now ready 2'
            ],
        );
        $this->assertEquals(1, $paginator->getTotalResults());
        $this->assertEquals(1, $paginator->getTotalPages());

        $firstPage = MondocLogService::getPaginatedLogsBetweenDates(
            $paginator,
            Date::modify()->minus()->days(1),
            Date::modify()->plus()->days(1),
            [
                'm' => 'My logger is now ready 2'
            ],
            1
        );
        $this->assertEquals('My logger is now ready 2', $firstPage[0]->getMessage());

        $lastLog->delete();
        $lastLogTwo->delete();
    }
}
