<?php

namespace Webman\ThinkOrm;

use Webman\Bootstrap;
use Workerman\Timer;
use Throwable;
use think\Paginator;
use think\facade\Db;
use think\DbManager;
use think\Container;

class ThinkOrm implements Bootstrap
{
    // 进程启动时调用
    public static function start($worker)
    {
        $config = config('thinkorm');
        // 配置
        Db::setConfig($config);
        // 维持mysql心跳
        if ($worker) {
            if (class_exists(Container::class, false)) {
                $manager_instance = Container::getInstance()->make(DbManager::class);
            } else {
                $reflect = new \ReflectionClass(Db::class);
                $property = $reflect->getProperty('instance');
                $property->setAccessible(true);
                $manager_instance = $property->getValue();
            }
            Timer::add(55, function () use ($manager_instance) {
                $reflect = new \ReflectionClass($manager_instance);
                $property = $reflect->getProperty('instance');
                $property->setAccessible(true);
                $instances  = $property->getValue($manager_instance);
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

        // 自定义分页组件类
        if($config['bootstrap'] && class_exists($config['bootstrap'])){
            Paginator::maker(function ($items, $listRows, $currentPage, $total, $simple, $options){
                return new $config['bootstrap']($items, $listRows, $currentPage, $total, $simple, $options);
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
