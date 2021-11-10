<?php

namespace fengdangxing\redis;

/**
 * @method mixed psetex($key, $ttl, $value)
 * @method mixed sScan($key, $iterator, $pattern = '', $count = 0)
 * @method mixed scan(&$iterator, $pattern = null, $count = 0)
 * @method mixed zScan($key, $iterator, $pattern = '', $count = 0)
 * @method mixed hScan($key, $iterator, $pattern = '', $count = 0)
 * @method mixed client($command, $arg = '')
 * @method mixed slowlog($command)
 * @method mixed open($host, $port = 6379, $timeout = 0.0, $retry_interval = 0)
 * @method mixed close()
 * @method mixed setOption($name, $value)
 * @method mixed getOption($name)
 * @method mixed ping()
 * @method mixed setex($key, $ttl, $value)
 * @method mixed setnx($key, $value)
 * @method mixed del($key1, $key2 = null, $key3 = null)
 * @method mixed delete($key1, $key2 = null, $key3 = null)
 * @method mixed multi($mode)
 * @method mixed exec()
 * @method mixed discard()
 * @method mixed watch($key)
 * @method mixed unwatch()
 * @method mixed subscribe($channels, $callback)
 * @method mixed psubscribe($patterns, $callback)
 * @method mixed publish($channel, $message)
 * @method mixed pubsub($keyword, $argument)
 * @method mixed exists($key)
 * @method mixed incr($key)
 * @method mixed incrByFloat($key, $increment)
 * @method mixed incrBy($key, $value)
 * @method mixed decr($key)
 * @method mixeddecrBy($key, $value)
 * @method getMultiple(array $keys)
 * @method lPush($key, $value1, $value2 = null, $valueN = null)
 * @method rPush($key, $value1, $value2 = null, $valueN = null)
 * @method lPushx($key, $value)
 * @method rPushx($key, $value)
 * @method lPop($key)
 * @method rPop($key)
 */
class Redis
{
    private static $connections;
    private static $host;
    private static $port;
    private static $auth;
    private static $timeout;
    private static $type_connect;//长连接或者短连接(默认)

    /**
     * Redis constructor.
     * @param $host
     * @param $port
     * @param null $auth
     * @param int $timeout
     * @param bool $type_connect false-短连接 true-长连接
     */
    public function __construct($host, $port, $auth = null, $timeout = 0, $type_connect = false)
    {
        self::$host = $host;
        self::$port = $port;
        self::$auth = $auth;
        self::$timeout = $timeout;
        self::$type_connect = $type_connect;
    }

    /**
     * 架构函数
     * @return \Redis
     * @access public
     */
    private static function getRedis()//两个参数要连接的服务器KEY,要选择的库
    {
        if (!self::$connections) {  //判断连接池中是否存在
            $func = self::$type_connect ? 'pconnect' : 'connect';
            self::$connections = new \Redis;
            self::$timeout === false ?
                self::$connections->$func(self::$host, self::$port) :
                self::$connections->$func(self::$host, self::$port, self::$timeout);
            self::$auth ? self::$connections->auth(self::$auth) : null;
        }
        //self::$connections->select(0);
        return self::$connections;
    }

    /**
     * @desc 功能描述
     * @author 1
     * @version v2.1
     * @date: 2021/11/10
     * @return \Redis
     */
    public static function getRedis_con()//两个参数要连接的服务器KEY,要选择的库
    {
        if (!self::$connections) {  //判断连接池中是否存在
            self::$connections = self::getRedis();
        }
        //self::$connections->select(0);
        return self::$connections;
    }
}
