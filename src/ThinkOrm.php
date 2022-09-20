<?php

namespace Webman\ThinkOrm;

use Webman\Bootstrap;
use Workerman\Timer;
use Throwable;
use think\Paginator;
use think\facade\Db;
use think\db\connector\Mysql;

class ThinkOrm implements Bootstrap
{
    // 进程启动时调用
    public static function start($worker)
    {
        $config = config('thinkorm');
        $default = $config['default'] ?? false;
        $connections = $config['connections'] ?? [];
        // 配置
        Db::setConfig($config);
        // 维持mysql心跳
        if ($worker) {
            Timer::add(55, function () use ($connections, $default) {
                $reflect = new \ReflectionClass(Db::class);
                $property = $reflect->getProperty('instance');
                $property->setAccessible(true);
                $instance = $property->getValue();
                $reflect = new \ReflectionClass($property->getValue());
                $property = $reflect->getProperty('instance');
                $property->setAccessible(true);
                $instances  = $property->getValue($instance);
                foreach ($instances as $connection) {
                    /* @var \think\db\connector\Mysql $connection */
                    if ($connection->getConfig('type') == 'mysql') {
                        try {
                            $connection->query('select 1');
                        } catch (Throwable $e) {}
                    }
                }
                Db::getDbLog(true);
            });
        }

        Paginator::currentPageResolver(function ($pageName = 'page') {
            $page = request()->input($pageName, 1);
            if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int)$page >= 1) {
                return (int)$page;
            }
            return 1;
        });
    }
}
