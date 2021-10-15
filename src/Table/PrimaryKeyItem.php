<?php
namespace Baohan\Tablestore\Table;

/**
 * Class PrimaryKeyItem
 * @package Baohan\Tablestore\Table
 */
class PrimaryKeyItem
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
     * PrimaryKeyResponse constructor.
     * @param array $pk
     */
    public function __construct(array $pk)
    {
        $this->key = $pk[0];
        $this->val = $pk[1];
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
}