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

namespace District5\MondocLogHandler;

/**
 * @class MondocLogConfig
 * @package District5\MondocLogHandler\Handler
 */
class MondocLogConfig
{
    /**
     * @var MondocLogConfig|null
     */
    protected static MondocLogConfig|null $instance = null;

    /**
     * @var string|null
     */
    protected string|null $collectionName = 'mondoc_log';

    /**
     * @var string|null
     */
    protected string|null $connectionId = 'default';

    /**
     * Protected constructor to prevent direct instantiation
     */
    protected function __construct()
    {
    }

    /**
     * @return MondocLogConfig
     */
    public static function getInstance(): MondocLogConfig
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public function getCollectionName(): string
    {
        return $this->collectionName;
    }

    /**
     * @param string $collectionName
     * @return $this
     */
    public function setCollectionName(string $collectionName): static
    {
        $this->collectionName = $collectionName;
        return $this;
    }

    /**
     * @return string
     */
    public function getConnectionId(): string
    {
        return $this->connectionId;
    }

    /**
     * @param string $connectionId
     * @return $this
     */
    public function setConnectionId(string $connectionId): static
    {
        $this->connectionId = $connectionId;
        return $this;
    }
}
