<?php
namespace Test\Table;

use Aliyun\OTS\Consts\ColumnTypeConst;
use Aliyun\OTS\Consts\ComparatorTypeConst;
use Aliyun\OTS\OTSClientException;
use Aliyun\OTS\OTSServerException;
use Baohan\Tablestore\Table\PrimaryKey;
use Baohan\Tablestore\Table\TableStore;
use Baohan\Tablestore\Table\TSTable;
use PHPUnit\Framework\TestCase;

class TSTableTest extends TestCase
{
    /**
     * @var TSTable
     */
    protected TSTable $table;

    protected function setUp(): void
    {
        parent::setUp();
        
        $endpoint = '';
        $accessKeyId = '';
        $accessKeySecret = '';
        $instanceName = '';
        $client = TableStore::getInstance(
            $endpoint,
            $accessKeyId,
            $accessKeySecret,
            $instanceName
        );
        $tableName = "";
        $this->table = new TSTable($client, $tableName);
    }
    
    /**
     * @throws OTSClientException
     * @throws OTSServerException
     * @return PrimaryKey
     */
    public function testPut(): PrimaryKey
    {
        $pk = new PrimaryKey();
        $pk['mirror_id'] = 'mock-mirror-id';
        $pk['timestamp'] = 1554798209501;
        $data = [
            'created' => 1554798209,
            'message' => 'mock-column-named-message',
            'price'   => [1.088, ColumnTypeConst::CONST_DOUBLE]
        ];
        $res = $this->table->put($pk, $data);
        $this->assertGreaterThanOrEqual(1, $res->getConsumedWrite());
        $this->assertGreaterThanOrEqual(0, $res->getConsumedRead());
        
        return $pk;
    }
    
    /**
     * @param PrimaryKey $pk
     * @return PrimaryKey
     * @throws OTSClientException
     * @throws OTSServerException
     * @depends testPut
     */
    public function testUpdate(PrimaryKey $pk): PrimaryKey
    {
        $data = [
            'price'   => [2.088, ColumnTypeConst::CONST_DOUBLE]
        ];
        $columnCondition = [
            'column_name' => 'price',
            'value' => 1.0,
            'comparator' => ComparatorTypeConst::CONST_GREATER_THAN
        ];
        $res = $this->table->update($pk, $data, $columnCondition);
        $this->assertGreaterThanOrEqual(1, $res->getConsumedWrite());
        $this->assertGreaterThanOrEqual(0, $res->getConsumedRead());
        
        return $pk;
    }
    
    /**
     * @param PrimaryKey $pk
     * @return PrimaryKey
     * @throws OTSClientException
     * @throws OTSServerException
     * @depends testUpdate
     */
    public function testTrim(PrimaryKey $pk): PrimaryKey
    {
        $column = ['created'];
        $res = $this->table->trim($pk, $column);
        $this->assertGreaterThanOrEqual(1, $res->getConsumedWrite());
        $this->assertGreaterThanOrEqual(0, $res->getConsumedRead());
        return $pk;
    }
    
    /**
     * @param PrimaryKey $pk
     * @return PrimaryKey
     * @throws OTSClientException
     * @throws OTSServerException
     * @depends testTrim
     */
    public function testFindOne(PrimaryKey $pk): PrimaryKey
    {
        $item = $this->table->findOne($pk);
        $this->assertEquals('mock-mirror-id', $item['mirror_id']);
        $this->assertEquals(1554798209501, $item['timestamp']);
        $this->assertEquals(false, array_key_exists('created', $item));
        $this->assertEquals('mock-column-named-message', $item['message']);
        $this->assertEquals(2.088, $item['price']);
        return $pk;
    }
    
    /**
     * @throws OTSClientException
     * @throws OTSServerException
     * @depends testFindOne
     */
    public function testDel(PrimaryKey $pk)
    {
        $res = $this->table->delete($pk);
        $this->assertGreaterThanOrEqual(1, $res->getConsumedWrite());
        $this->assertGreaterThanOrEqual(0, $res->getConsumedRead());
    }

    public function testPuts()
    {
        $arr = [];
        for($i = 0; $i < 10; $i++) {
            $pk = new PrimaryKey();
            $pk['mirror_id'] = 'mirror-id-mock';
            $pk['timestamp'] = 1554798209501 + $i;
            $columns = [
                'message' => 'mock-attribute-column-named-message'
            ];
            $arr[] = [$pk, $columns];
        }
        $res = $this->table->puts($arr);
        $third = $res->getTSResponses()[2];
        $this->assertEquals(true, $res->allOK());
        $this->assertEquals(true, $third->isOk());
    }

    public function testUpdates()
    {
        $arr = [];
        for($i = 0; $i < 10; $i++) {
            $pk = new PrimaryKey();
            $pk['mirror_id'] = 'mirror-id-mock';
            $pk['timestamp'] = 1554798209501 + $i;
            $columns = [
                'updated' => 'comment-for-update'
            ];
            $arr[] = [$pk, $columns];
        }
        $res = $this->table->updates($arr);
        $third = $res->getTSResponses()[2];
        $this->assertEquals(true, $res->allOK());
        $this->assertEquals(true, $third->isOk());
    }
    
    public function testTrims()
    {
        $arr = [];
        for($i = 0; $i < 10; $i++) {
            $pk = new PrimaryKey();
            $pk['mirror_id'] = 'mirror-id-mock';
            $pk['timestamp'] = 1554798209501 + $i;
            $columns = [
                'message'
            ];
            $arr[] = [$pk, $columns];
        }
        $res = $this->table->trims($arr);
        $third = $res->getTSResponses()[2];
        $this->assertEquals(true, $res->allOK());
        $this->assertEquals(true, $third->isOk());
    }
    
    /**
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function testFind()
    {
        $spk = new PrimaryKey();
        $spk['mirror_id'] = 'mirror-id-mock';
        $spk['timestamp'] = $spk->min();
        $epk = new PrimaryKey();
        $epk['mirror_id'] = 'mirror-id-mock';
        $epk['timestamp'] = $epk->max();
        $items = $this->table->find(
            $spk,
            $epk,
            1,
            10,
            ['updated', 'message']
        );
        $this->assertCount(10, $items);
        $this->assertEquals('comment-for-update', $items[0]['updated']);
        $this->assertArrayNotHasKey('message', $items[0]);
    }
    
    /**
     * @throws OTSClientException
     * @throws OTSServerException
     */
    public function testGets()
    {
        $pk1 = new PrimaryKey();
        $pk1['mirror_id'] = 'mirror-id-mock';
        $pk1['timestamp'] = 1554798209502;
        $pk2 = new PrimaryKey();
        $pk2['mirror_id'] = 'mirror-id-mock';
        $pk2['timestamp'] = 1554798209503;
        $projects = ['updated', 'message'];
        $items = $this->table->gets([$pk1, $pk2], $projects);
        $this->assertCount(2, $items);
        $this->assertEquals('comment-for-update', $items[0]['updated']);
        $this->assertArrayNotHasKey('message', $items[0]);
    }
    
    public function testDeletes()
    {
        $arr = [];
        for($i = 0; $i < 10; $i++) {
            $pk = new PrimaryKey();
            $pk['mirror_id'] = 'mirror-id-mock';
            $pk['timestamp'] = 1554798209501 + $i;
            $columns = null;
            $arr[] = [$pk, $columns];
        }
        $res = $this->table->deletes($arr);
        $this->assertEquals(true, $res->allOK());
    }
}
