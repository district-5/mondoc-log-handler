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


use District5\MondocLogHandler\Handler\MondocLogHandler;
use District5\MondocLogHandler\MondocLogConfig;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;

/**
 * Class MondocLogHandlerTest
 * @package District5Tests\MondocLogHandlerTests
 */
class MondocLogHandlerTest extends TestAbstract
{
    public function testLog()
    {
        $this->setupConfig();

        $logger = new Logger('my_logger');
        $logger->pushHandler(
            $handler = new MondocLogHandler()
        );
        $logger->info('My logger is now ready');
        $this->assertNotNull($handler->getLastLog());

        $lastLog = $handler->getLastLog();
        $this->assertTrue($lastLog->hasObjectId());
        $this->assertEquals('My logger is now ready', $lastLog->getMessage());
        $this->assertEquals('Info', $lastLog->getLevelName());
        $this->assertEquals(200, $lastLog->getLevel()->value);
        $this->assertEquals('my_logger', $lastLog->getChannel());
        $lastLog->delete();
    }

    public function testFormatterDefault()
    {
        $this->setupConfig();

        $logger = new Logger('my_logger');

        $handler = new MondocLogHandler();
        $handler->setFormatter(
            new LineFormatter('%message%') // this is the default formatter and format
        );
        $logger->pushHandler(
            $handler
        );
        $logger->info('My logger is now ready');
        $this->assertNotNull($handler->getLastLog());
        $lastLog = $handler->getLastLog();
        $this->assertTrue($lastLog->hasObjectId());
        $this->assertEquals('My logger is now ready', $lastLog->getMessage());
        $lastLog->delete();
    }

    public function testFormatterCustom()
    {
        $this->setupConfig();

        $logger = new Logger('my_logger2');
        $handler = new MondocLogHandler();
        $logger->pushHandler(
            $handler
        );
        $handler->setFormatter(
            new LineFormatter('%message% %context% %extra%')
        );
        $logger->info(
            'My logger is now ready',
            [
                'foo' => 'bar',
            ]
        );
        $this->assertNotNull($handler->getLastLog());
        $lastLog = $handler->getLastLog();
        $this->assertTrue($lastLog->hasObjectId());
        $this->assertEquals('My logger is now ready {"foo":"bar"} []', $lastLog->getMessage());
        $lastLog->delete();
    }

    public function testLogWithContext()
    {
        $this->setupConfig();

        $logger = new Logger('my_logger');

        $logger->pushHandler(
            $handler = new MondocLogHandler()
        );
        $logger->info(
            'My logger is now ready',
            [
                'a' => 'data',
                'b' => 'goes-here'
            ]
        );
        $this->assertNotNull($handler->getLastLog());

        $lastLog = $handler->getLastLog();
        $this->assertTrue($lastLog->hasObjectId());
        $this->assertEquals('My logger is now ready', $lastLog->getMessage());
        $this->assertEquals('data', $lastLog->getContext()['a']);
        $this->assertEquals('goes-here', $lastLog->getContext()['b']);
        $lastLog->delete();
    }

    public function testLogWithProcessorForExtra()
    {
        $config = MondocLogConfig::getInstance()->setConnectionId(
            'default'
        )->setCollectionName(
            'mondoc_log_tests'
        );
        $this->assertInstanceOf(MondocLogConfig::class, $config);
        $this->assertEquals('mondoc_log_tests', $config->getCollectionName());
        $this->assertEquals('default', $config->getConnectionId());

        $logger = new Logger('my_logger');

        $logger->pushHandler(
            $handler = new MondocLogHandler()
        );
        $handler->pushProcessor(
            new UidProcessor()
        );
        $logger->info(
            'My logger is now ready',
            [
                'a' => 'data',
                'b' => 'goes-here'
            ]
        );
        $this->assertNotNull($handler->getLastLog());

        $lastLog = $handler->getLastLog();
        $this->assertTrue($lastLog->hasObjectId());
        $this->assertEquals('My logger is now ready', $lastLog->getMessage());
        $this->assertEquals('data', $lastLog->getContext()['a']);
        $this->assertEquals('goes-here', $lastLog->getContext()['b']);

        $this->assertArrayHasKey('uid', $lastLog->getExtra());
        $lastLog->delete();
    }
}
