<?php
namespace Baohan\Tablestore\Table;

use Aliyun\OTS\Consts\DirectionConst;
use Aliyun\OTS\Consts\OperationTypeConst;
use Aliyun\OTS\Consts\RowExistenceExpectationConst;
use Aliyun\OTS\OTSClient;
use Aliyun\OTS\OTSClientException;
use Aliyun\OTS\OTSServerException;

class TSTable
{
    /**
     * @var string
     */
    private string $name;
    
    /**
     * 最大版本
     * @var int
     */
    private int $maxVersion = 1;

    /**
     * @var OTSClient
     */
    protected OTSClient $client;

    /**
     * TSTable constructor.
     * @param OTSClient $client
     * @param string $name
     */
    public function __construct(OTSClient $client, string $name)
    {
        $this->client = $client;
        $this->name = $name;
    }
    
    /**
     * put multiple records in bulk
     * @param array $map
     * @return TSResponses
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function puts(array $map = []): TSResponses
    {
        $request = function(PrimaryKey $pk, array $column) {
            return [
                'operation_type'    => OperationTypeConst::CONST_PUT,
                'condition'         => RowExistenceExpectationConst::CONST_IGNORE,
                'primary_key'       => $pk->toArray(),
                'attribute_columns' => $this->toColumn($column)
            ];
        };
        return $this->bulk($map, $request);
    }
    
    /**
     * updates multiple records in bulk
     * @param array $map
     * @return TSResponses
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function updates(array $map = []): TSResponses
    {
        $request = function(PrimaryKey $pk, array $column) {
            return [
                'operation_type'    => OperationTypeConst::CONST_UPDATE,
                'condition'         => RowExistenceExpectationConst::CONST_IGNORE,
                'primary_key'       => $pk->toArray(),
                'update_of_attribute_columns' => [
                    'PUT' => $this->toColumn($column)
                ]
            ];
        };
        return $this->bulk($map, $request);
    }
    
    /**
     * trim fields for multiple records in bulk
     * @param array $map
     * @return TSResponses
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function trims(array $map = []): TSResponses
    {
        $request = function(PrimaryKey $pk, array $column) {
            return [
                'operation_type'    => OperationTypeConst::CONST_UPDATE,
                'condition'         => RowExistenceExpectationConst::CONST_IGNORE,
                'primary_key'       => $pk->toArray(),
                'update_of_attribute_columns' => [
                    'DELETE_ALL' => $column
                ]
            ];
        };
        return $this->bulk($map, $request);
    }
    
    /**
     * delete multiple records in bulk
     * @param array $map
     * @return TSResponses
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function deletes(array $map = []): TSResponses
    {
        $request = function(PrimaryKey $pk) {
            return [
                'operation_type'    => OperationTypeConst::CONST_DELETE,
                'condition'         => RowExistenceExpectationConst::CONST_IGNORE,
                'primary_key'       => $pk->toArray()
            ];
        };
        return $this->bulk($map, $request);
    }
    
    /**
     * updates multiple records in bulk
     * @param array $map
     * @param \Closure $request
     * @return TSResponses
     * @throws OTSClientException
     * @throws OTSServerException
     */
    protected function bulk(array $map, \Closure $request): TSResponses
    {
        $rows = function($map) use ($request) {
            $arr = [];
            /**
             * @var PrimaryKey $pk
             * @var array $columns
             */
            foreach ($map as $item) {
                list($pk, $columns) = $item;
                if ($columns) {
                    $arr[] = $request($pk, $columns);
                } else {
                    $arr[] = $request($pk);
                }
            }
            return $arr;
        };
        $response = $this->client->batchWriteRow(
            ['tables' => [['table_name' => $this->name, 'rows' => $rows($map)]]]
        );
        return new TSResponses($response);
    }
    
    /**
     * create record
     * create new one if there isn't exist yet
     * @param PrimaryKey $pk
     * @param array $data
     * @return TSResponse
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function put(PrimaryKey $pk, array $data = []): TSResponse
    {
        $request = [
            'table_name'        => $this->name,
            'condition'         => RowExistenceExpectationConst::CONST_IGNORE,
            'primary_key'       => $pk->toArray(),
            'attribute_columns' => $this->toColumn($data)
        ];

        $response = $this->client->putRow($request);
        return new TSResponse($response);
    }
    
    /**
     * update attributes columns
     * @param PrimaryKey $pk
     * @param array $data
     * @param array $condition update document if it is satisfied condition
     * @return TSResponse
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function update(PrimaryKey $pk, array $data = [], array $condition = []): TSResponse
    {
        $request = [
            'table_name'  => $this->name,
            'condition'   => [
                'row_existence' => RowExistenceExpectationConst::CONST_IGNORE
            ],
            'primary_key' => $pk->toArray(),
            'update_of_attribute_columns' => [
                'PUT' => $this->toColumn($data)
            ]
        ];

        if ($condition) {
            $request['condition']['column_condition'] = $condition;
        }

        $response = $this->client->updateRow($request);
        return new TSResponse($response);
    }
    
    /**
     * trim attributes columns
     * @param PrimaryKey $pk
     * @param array $cols names of column
     * @return TSResponse
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function trim(PrimaryKey $pk, array $cols = []): TSResponse
    {
        $request = [
            'table_name'        => $this->name,
            'condition'         => RowExistenceExpectationConst::CONST_IGNORE,
            'primary_key'       => $pk->toArray(),
            'update_of_attribute_columns' => [
                'DELETE_ALL' => $cols
            ]
        ];

        $response = $this->client->updateRow($request);
        return new TSResponse($response);
    }
    
    /**
     * @param PrimaryKey $pk
     * @param array $projects
     * @return array
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function findOne(PrimaryKey $pk, array $projects = []): array
    {
        $request = [
            'table_name'   => $this->name,
            'primary_key'  => $pk->toArray(),
            'max_versions' => $this->maxVersion
        ];
        
        if ($projects) {
            $request['columns_to_get'] = $projects;
        }

        $response = $this->client->getRow($request);

        return array_merge(
            $this->toMap($response['primary_key']),
            $this->toMap($response['attribute_columns'])
        );
    }

    /**
     * @param PrimaryKey $spk
     * @param PrimaryKey $epk
     * @param int $order direction
     * @param int $limit
     * @param array $projects
     * @return array
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function find(PrimaryKey $spk, PrimaryKey $epk, int $order = 1, int $limit = 10, array $projects = [])
    {
        $items = [];
        $sp = $spk->toArray();
        $ep = $epk->toArray();
        while (!empty($sp) && $limit > 0) {
            $request = [
                'table_name' => $this->name,
                'max_versions' => $this->maxVersion,
                'direction' => $order >= 0 ? DirectionConst::CONST_FORWARD : DirectionConst::CONST_BACKWARD,
                'inclusive_start_primary_key' => $sp,
                'exclusive_end_primary_key' => $ep,
                'limit' => $limit
            ];

            if($projects) $request['columns_to_get'] = $projects;

            $response = $this->client->getRange($request);
            
            foreach($response['rows'] as $row) {
                $items[] = array_merge(
                    $this->toMap($row['primary_key']),
                    $this->toMap($row['attribute_columns'])
                );
                $limit--;
            }
            $sp = $response['next_start_primary_key'];
        }
        return $items;
    }
    
    /**
     * @param PrimaryKey[] $pks
     * @param array $projects
     * @return array
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function gets(array $pks, array $projects = []): array
    {
        $table = [
            'table_name'   => $this->name,
            'max_versions' => 1,
            'primary_keys' => array_map(function(PrimaryKey $pk) { return $pk->toArray(); }, $pks),
        ];
        if ($projects) {
            $table['columns_to_get'] = $projects;
        }
        $response = $this->client->batchGetRow(['tables' => [$table]]);
        $responses = new TSResponses($response);
        $items = [];
        foreach ($responses->getTSResponses() as $res) {
            $raw = $res->getResponse();
            $items[] = array_merge(
                $this->toMap($raw['primary_key']),
                $this->toMap($raw['attribute_columns'])
            );
        }
        return $items;
    }
    
    /**
     * @param PrimaryKey $pk
     * @return TSResponse
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function delete(PrimaryKey $pk): TSResponse
    {
        $request = [
            'table_name'  => $this->name,
            'condition'   => RowExistenceExpectationConst::CONST_IGNORE,
            'primary_key' => $pk->toArray()
        ];
        $response = $this->client->deleteRow($request);
        return new TSResponse($response);
    }
    
    /**
     * @param array $items
     * @return array
     */
    protected function toMap(array $items): array
    {
        $map = [];
        foreach ($items as $item) $map[$item[0]] = $item[1];
        return $map;
    }
    
    /**
     * @param array $data
     * @return array
     */
    protected function toColumn(array $data = []): array
    {
        $columns = [];
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $columns[] = [$k, ...$v];
            } else {
                $columns[] = [$k, $v];
            }
        }
        return $columns;
    }
}