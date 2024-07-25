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


/**
 * Class MondocLogConfigTest
 * @package District5Tests\MondocLogHandlerTests
 */
class MondocLogConfigTest extends TestAbstract
{
    public function testConfig()
    {
        $config = $this->setupConfig();

        $config->setCollectionName('test_collection');
        $config->setConnectionId('test_connection');

        $this->assertEquals('test_collection', $config->getCollectionName());
        $this->assertEquals('test_connection', $config->getConnectionId());
    }
}
