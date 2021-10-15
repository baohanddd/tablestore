<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: table_store.proto

namespace Aliyun\OTS\ProtoBuffer\Protocol;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>aliyun.OTS.ProtoBuffer.Protocol.GetShardIteratorRequest</code>
 */
class GetShardIteratorRequest extends \Aliyun\OTS\ProtoBuffer\Protocol\Message
{
    /**
     * Generated from protobuf field <code>required string stream_id = 1;</code>
     */
    private $stream_id = '';
    private $has_stream_id = false;
    /**
     * Generated from protobuf field <code>required string shard_id = 2;</code>
     */
    private $shard_id = '';
    private $has_shard_id = false;

    public function __construct() {
        \GPBMetadata\TableStore::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>required string stream_id = 1;</code>
     * @return string
     */
    public function getStreamId()
    {
        return $this->stream_id;
    }

    /**
     * Generated from protobuf field <code>required string stream_id = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setStreamId($var)
    {
        GPBUtil::checkString($var, True);
        $this->stream_id = $var;
        $this->has_stream_id = true;

        return $this;
    }

    public function hasStreamId()
    {
        return $this->has_stream_id;
    }

    /**
     * Generated from protobuf field <code>required string shard_id = 2;</code>
     * @return string
     */
    public function getShardId()
    {
        return $this->shard_id;
    }

    /**
     * Generated from protobuf field <code>required string shard_id = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setShardId($var)
    {
        GPBUtil::checkString($var, True);
        $this->shard_id = $var;
        $this->has_shard_id = true;

        return $this;
    }

    public function hasShardId()
    {
        return $this->has_shard_id;
    }

}
