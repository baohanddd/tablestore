<?php
namespace Baohan\Tablestore\Table;

use ArrayIterator;

/**
 * Class AttributeColumnsResponse
 * @package Baohan\Tablestore\Table
 */
class AttributeColumnsResponse implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @var AttributeColumnResponse[]
     */
    protected array $items = [];

    public function __construct(array $attrs)
    {
        foreach ($attrs as $attr) {
            $acr = new AttributeColumnResponse($attr);
            $this->items[$acr->getKey()] = $acr;
        }
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator(array_values($this->items));
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[] = [$item->getKey(), $item->getValue(), $item->getType(), $item->getVersion()];
        }
        return $items;
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
     * @return AttributeColumnResponse|null
     */
    public function offsetGet($offset): ?AttributeColumnResponse
    {
        if ($this->offsetExists($offset)) {
            return $this->items[$offset];
        }
        return null;
    }

    /**
     * @param string $offset
     * @param AttributeColumnResponse $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->items[$offset] = $value;
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->items[$offset]);
        }
    }
}