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

use District5\Mondoc\MondocConfig;
use District5\MondocLogHandler\MondocLogConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class TestAbstract
 * @package District5Tests\MondocLogHandlerTests
 */
class TestAbstract extends TestCase
{
    /**
     * @var null|MondocConfig
     */
    protected MondocConfig|null $mondoc = null;

    protected function tearDown(): void
    {
    }

    protected function setUp(): void
    {
    }

    /**
     * @return string
     */
    protected function getDatabaseName(): string
    {
        return getenv('MONGO_DATABASE') . php_uname('s');
    }

    /**
     * @return MondocLogConfig
     */
    protected function setupConfig(): MondocLogConfig
    {
        $config = MondocLogConfig::getInstance()->setConnectionId(
            'default'
        )->setCollectionName(
            'mondoc_log_tests'
        );
        $this->assertInstanceOf(MondocLogConfig::class, $config);
        $this->assertEquals('mondoc_log_tests', $config->getCollectionName());
        $this->assertEquals('default', $config->getConnectionId());

        return $config;
    }
}
