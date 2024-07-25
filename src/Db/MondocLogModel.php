<?php

namespace District5\MondocLogHandler\Db;

use District5\Mondoc\Db\Model\MondocAbstractModel;
use District5\Mondoc\Db\Model\Traits\MondocCreatedDateTrait;
use Monolog\Level;

/**
 * @class MondocLogModel
 * @package District5\MondocLogHandler\Db
 */
class MondocLogModel extends MondocAbstractModel
{
    use MondocCreatedDateTrait;

    /**
     * @var string|null
     */
    protected string|null $channel = null;

    /**
     * @var int|null
     */
    protected int|null $level = null;

    /**
     * @var string|null
     */
    protected string|null $levelName = null;

    /**
     * @var string|null
     */
    protected string|null $message = null;

    /**
     * @var array
     */
    protected array $extra = [];

    /**
     * @var array
     */
    protected array $context = [];

    /**
     * An array holding original key to new keys.
     *
     * @example
     *    [
     *        'dbKey' => 'friendlyLocalFullKey'
     *    ]
     *
     * @var string[]
     */
    protected array $mondocFieldAliases = [
        'c' => 'channel',
        'l' => 'level',
        'ln' => 'levelName',
        'm' => 'message',
        'ex' => 'extra',
        'cx' => 'context',
    ];

    /**
     * @return string|null
     */
    public function getChannel(): string|null
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     * @return $this
     */
    public function setChannel(string $channel): static
    {
        $this->channel = $channel;
        $this->addDirty('channel');
        return $this;
    }

    /**
     * @return Level|null
     */
    public function getLevel(): Level|null
    {
        return Level::tryFrom(
            $this->level
        );
    }

    /**
     * @param Level $level
     * @return $this
     */
    public function setLevel(Level $level): static
    {
        $this->level = $level->value;
        $this->addDirty('level');

        return $this->setLevelName(
            $level->name
        );
    }

    /**
     * @return string|null
     */
    public function getLevelName(): string|null
    {
        return $this->levelName;
    }

    /**
     * @param string $levelName
     * @return $this
     */
    public function setLevelName(string $levelName): static
    {
        $this->levelName = $levelName;
        $this->addDirty('levelName');
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): string|null
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;
        $this->addDirty('message');
        return $this;
    }

    /**
     * @return array
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * @param array $extra
     * @return $this
     */
    public function setExtra(array $extra): static
    {
        $this->extra = $extra;
        $this->addDirty('extra');
        return $this;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array $context
     * @return $this
     */
    public function setContext(array $context): static
    {
        $this->context = $context;
        $this->addDirty('context');
        return $this;
    }
}
