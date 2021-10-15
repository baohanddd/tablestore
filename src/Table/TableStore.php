<?php
namespace Baohan\Tablestore\Table;

use Aliyun\OTS\OTSClient as OTSClient;
use Aliyun\OTS\Retry\DefaultRetryPolicy;

class TableStore
{
    /**
     * @param string $endPoint
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @param string $instanceName
     * @return OTSClient
     */
    public static function getInstance(
        string $endPoint, string $accessKeyId, string $accessKeySecret, string $instanceName)
    {
        $client = new OTSClient([
            'EndPoint' => $endPoint,
            'AccessKeyID' => $accessKeyId,
            'AccessKeySecret' => $accessKeySecret,
            'InstanceName' => $instanceName,
            // 以下是可选参数
            'ConnectionTimeout' => 5.0,                      # 与OTS建立连接的最大延时，默认 2.0秒
            'SocketTimeout' => 5.0,                          # 每次请求响应最大延时，默认2.0秒

            // 重试策略，默认为 DefaultRetryPolicy
            // 如果要关闭重试，可以设置为： 'RetryPolicy' => new NoRetryPolicy(),
            // 如果要自定义重试策略，你可以继承 \Aliyun\OTS\Retry\RetryPolicy 接口构造自己的重试策略
            'RetryPolicy' => new DefaultRetryPolicy(),

            // Error级别日志处理函数，用来打印OTS服务端返回错误时的日志
            // 如果设置为null则为关闭log
            'ErrorLogHandler' => function($message) {},

            // Debug级别日志处理函数，用来打印正常的请求和响应信息
            // 如果设置为null则为关闭log
            'DebugLogHandler' => function($message) {},
        ]);
        return $client;
    }
}