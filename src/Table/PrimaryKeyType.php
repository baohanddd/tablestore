<?php
namespace Baohan\Tablestore\Table;

/**
 * Class PrimaryKeyType
 * @package Baohan\Tablestore\Table
 */
class PrimaryKeyType
{
    /**
     * @var string
     */
    protected string $typeConst;

    /**
     * PrimaryKeyType constructor.
     * @param string $const
     */
    protected function __construct(string $const)
    {
        $this->typeConst = $const;
    }

    /**
     * @param string $const
     * @return PrimaryKeyType
     */
    public static function getInstance(string $const): PrimaryKeyType
    {
        return new PrimaryKeyType($const);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->typeConst;
    }
}