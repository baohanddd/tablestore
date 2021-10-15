<?php
namespace Baohan\Tablestore\Table;

use Aliyun\OTS\Consts\PrimaryKeyTypeConst;
use ArrayIterator;
use Exception;

/**
 * Class PrimaryKeys
 * @package Baohan\Tablestore\Table
 */
class PrimaryKey implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @var array
     */
    protected array $items = [];

    /**
     * PrimaryKey constructor.
     * @param array $pks
     */
    public function __construct(array $pks = [])
    {
        foreach ($pks as $pk) {
            $this->items[$pk[0]] = $pk[1];
        }
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
    
    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return count($this->items) == 0;
    }

    /**
     * @return PrimaryKeyType
     */
    public function max(): PrimaryKeyType
    {
        return PrimaryKeyType::getInstance(PrimaryKeyTypeConst::CONST_INF_MAX);
    }

    /**
     * @return PrimaryKeyType
     */
    public function min(): PrimaryKeyType
    {
        return PrimaryKeyType::getInstance(PrimaryKeyTypeConst::CONST_INF_MIN);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $arr = [];
        foreach ($this->items as $k => $v) {
            if ($v instanceof PrimaryKeyType) {
                $arr[] = [$k, null, $v->toString()];
            } else {
                $arr[] = [$k, $v];
            }
        }
        return $arr;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * @param mixed $offset
     * @return mixed
     * @throws Exception
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->items[$offset];
        }
        throw new Exception("can not read `{$offset}` from primary key...", 400);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
//        if ($value instanceof PrimaryKeyType) {
//            $this->items[$offset] = $value->toArray();
//        } else {
            $this->items[$offset] = $value;
//        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->items[$offset]);
        }
    }
}