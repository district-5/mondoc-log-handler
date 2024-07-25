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

namespace District5\MondocLogHandler\Handler;

use District5\Mondoc\MondocConfig;
use District5\MondocLogHandler\Db\MondocLogModel;
use District5\MondocLogHandler\Db\MondocLogService;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * @class MondocLogHandler
 * @package District5\MondocLogHandler\Handler
 */
class MondocLogHandler extends AbstractProcessingHandler
{
    /**
     * @var bool
     */
    private bool $setup = false;

    /**
     * @var MondocLogModel|null
     */
    private MondocLogModel|null $lastLog = null;

    /**
     * @param Level $level
     * @param bool $bubble
     */
    public function __construct(Level $level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    /**
     * @param LogRecord $record
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        if (!$this->setup) {
            $this->setup();
        }

        $this->lastLog = MondocLogService::createFromLogRecord($record);
    }

    /**
     * @return void
     */
    private function setup(): void
    {
        if ($this->setup === false) {
            MondocConfig::getInstance()->addServiceMapping(
                MondocLogModel::class,
                MondocLogService::class
            );
            $this->setup = true;
        }
    }

    /**
     * @return FormatterInterface
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LineFormatter('%message%');
    }

    /**
     * @return MondocLogModel|null
     */
    public function getLastLog(): MondocLogModel|null
    {
        return $this->lastLog;
    }
}
