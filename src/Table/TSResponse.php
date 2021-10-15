<?php
namespace Baohan\Tablestore\Table;

class TSResponse
{
    /**
     * @var array
     */
    protected array $response = [];

    /**
     * @var array
     */
    protected array $consumed = [
        'read'  => 0,
        'write' => 0
    ];

    /**
     * 返回的自增主键（如果有的话）
     * @var PrimaryKey|null
     */
    protected ?PrimaryKey $pks;

    /**
     * 属性列
     * @var AttributeColumnsResponse|null
     */
    protected ?AttributeColumnsResponse $attrs;

    /**
     * @var bool
     */
    protected bool $isOk = false;

    /**
     * TSResponse constructor.
     * @param array $response
     */
    public function __construct(array $response)
    {
//        var_dump($response);
        $this->response = $response;
        $this->consumed['read']  = $this->response['consumed']['capacity_unit']['read'];
        $this->consumed['write'] = $this->response['consumed']['capacity_unit']['write'];
        if (array_key_exists('is_ok', $this->response)) {
            $this->isOk = (bool) $this->response['is_ok'];
        }
        if ($this->response['primary_key']) {
            $this->pks = new PrimaryKey($this->response['primary_key']);
        }
        if ($this->response['attribute_columns']) {
            $this->attrs = new AttributeColumnsResponse($this->response['attribute_columns']);
        }
    }
    
    /**
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->isOk;
    }

    /**
     * @return int
     */
    public function getConsumedRead(): int
    {
        return $this->consumed['read'];
    }

    /**
     * @return int
     */
    public function getConsumedWrite(): int
    {
        return $this->consumed['write'];
    }

    /**
     * @return PrimaryKey|null
     */
    public function getPrimaryKey(): ?PrimaryKey
    {
        return $this->pks;
    }
    
    /**
     * @return AttributeColumnsResponse|null
     */
    public function getAttributeColumns(): ?AttributeColumnsResponse
    {
        return $this->attrs;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}