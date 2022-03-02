<?php
namespace Webman\ThinkOrm;

use Webman\Bootstrap;
use Workerman\Timer;
use think\facade\Db;

class ThinkOrm implements Bootstrap
{
    // 进程启动时调用
    public static function start($worker)
    {
        // 配置
        Db::setConfig(config('thinkorm'));
        // 维持mysql心跳
        if ($worker) {
            Timer::add(55, function () {
                $connections = config('thinkorm.connections', []);
                foreach ($connections as $key => $item) {
                    if ($item['type'] == 'mysql') {
                        Db::connect($key)->query('select 1');
                    }
                }
            });
        }
    }
}