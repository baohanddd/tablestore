<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: table_store.proto

namespace Aliyun\OTS\ProtoBuffer\Protocol;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>aliyun.OTS.ProtoBuffer.Protocol.ReturnContent</code>
 */
class ReturnContent extends \Aliyun\OTS\ProtoBuffer\Protocol\Message
{
    /**
     * Generated from protobuf field <code>optional .aliyun.OTS.ProtoBuffer.Protocol.ReturnType return_type = 1;</code>
     */
    private $return_type = 0;
    private $has_return_type = false;
    /**
     * Generated from protobuf field <code>repeated string return_column_names = 2;</code>
     */
    private $return_column_names;
    private $has_return_column_names = false;

    public function __construct() {
        \GPBMetadata\TableStore::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>optional .aliyun.OTS.ProtoBuffer.Protocol.ReturnType return_type = 1;</code>
     * @return int
     */
    public function getReturnType()
    {
        return $this->return_type;
    }

    /**
     * Generated from protobuf field <code>optional .aliyun.OTS.ProtoBuffer.Protocol.ReturnType return_type = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setReturnType($var)
    {
        GPBUtil::checkEnum($var, \Aliyun\OTS\ProtoBuffer\Protocol\ReturnType::class);
        $this->return_type = $var;
        $this->has_return_type = true;

        return $this;
    }

    public function hasReturnType()
    {
        return $this->has_return_type;
    }

    /**
     * Generated from protobuf field <code>repeated string return_column_names = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getReturnColumnNames()
    {
        return $this->return_column_names;
    }

    /**
     * Generated from protobuf field <code>repeated string return_column_names = 2;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setReturnColumnNames($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->return_column_names = $arr;
        $this->has_return_column_names = true;

        return $this;
    }

    public function hasReturnColumnNames()
    {
        return $this->has_return_column_names;
    }

}

