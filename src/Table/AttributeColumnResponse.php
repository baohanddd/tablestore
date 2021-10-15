<?php
namespace Baohan\Tablestore\Table;

/**
 * Class AttributeColumnResponse
 * @package Baohan\Tablestore\Table
 */
class AttributeColumnResponse
{
    /**
     * @var string
     */
    protected string $key;
    /**
     * @var string
     */
    protected string $val;
    /**
     * @var string
     */
    protected string $type;
    /**
     * @var int
     */
    protected int $version;

    /**
     * AttributeColumnResponse constructor.
     * @param array $pk
     */
    public function __construct(array $pk)
    {
        $this->key = $pk[0];
        $this->val = $pk[1];
        $this->type = $pk[2];
        $this->version = $pk[3];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [$this->key => $this->val];
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->val;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }
}