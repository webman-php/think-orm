<?php

namespace Webman\ThinkOrm;

use think\Container;
use think\Paginator;
use support\think\Db;
use Webman\Bootstrap;
use support\think\Cache;

class ThinkOrm implements Bootstrap
{
    /**
     * @var bool
     */
    private static bool $initialized = false;

    /**
     * @return void
     */
    public static function start($worker): void
    {
        if (self::$initialized) {
            return;
        }
        self::$initialized = true;

        $config = config('think-orm', config('thinkorm'));
        if (!$config) {
            return;
        }

        Container::getInstance()->bind('think\DbManager', DbManager::class);

        // 配置
        Db::setConfig($config);

        if (class_exists(Cache::class)) {
            Db::setCache(new ThinkCache());
        }

        Paginator::currentPageResolver(function ($pageName = 'page') {
            $request = request();
            if (!$request) {
                return 1;
            }
            $page = $request->input($pageName, 1);
            if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int)$page >= 1) {
                return (int)$page;
            }
            return 1;
        });

        // 设置分页url中域名与参数之间的path字符串
        Paginator::currentPathResolver(function () {
            $request = request();
            return $request ? $request->path() : '/';
        });

        // 设置自定义分页类
        $connections = $config['connections'] ?? [];
        foreach ($connections as $connection) {
            if (!empty($connection['bootstrap'])) {
                $bootstrapClass = $connection['bootstrap'];
                if (class_exists($bootstrapClass)) {
                    Paginator::maker(function ($items, $listRows, $currentPage, $total, $simple, $options) use ($bootstrapClass) {
                        return new $bootstrapClass($items, $listRows, $currentPage, $total, $simple, $options);
                    });
                    break; // 只需要设置一次
                }
            }
        }
    }
}
