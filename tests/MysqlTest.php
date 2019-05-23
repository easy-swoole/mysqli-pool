<?php


namespace EasySwoole\MysqliPool\Test;


use EasySwoole\Mysqli\Config;
use EasySwoole\Mysqli\Exceptions\ConnectFail;
use EasySwoole\MysqliPool\Mysql;
use PHPUnit\Framework\TestCase;

class MysqlTest extends TestCase
{
    public function testUp()
    {
        /*
         * 故意的密码错误链接，但这个服务端口确实存在mysql服务
         */
        $config1 = new Config(['host' => '120.25.207.44', 'user' => 'root', 'password' => '12345678', 'database' => 'test']);
        Mysql::getInstance()->register('my1', $config1);
        Mysql::getInstance()->pool('my1')->preLoad(5);
    }

    public function testGet()
    {
        /*
         * 如果这个testGet不放go 里面执行则不会出现Segmentation fault: 11
         */
        go(function (){
            $conn = Mysql::defer('my1');
            $list = $conn->get('user');
        });
    }

    public function testGetOne()
    {
        /*
         * 如果这个testGet不放go 里面执行则不会出现Segmentation fault: 11
         */
        $conn = Mysql::defer('my1');
        $list = $conn->get('user');
    }

    public function testGetTwo()
    {
        try{
            $conn = Mysql::defer('my1');
            $list = $conn->get('user');
        }catch (\Throwable $throwable){
            $this->assertInstanceOf(ConnectFail::class, $throwable);
        }
    }

}