<?php

namespace support\think;

use think\db\Query;
use think\model\Collection;

require_once __DIR__ . '/../../Initializer.php';

/**
 * @method static Query where(mixed $field, string $op = null, mixed $condition = null)  查询条件
 * @method static Query whereTime(string $field, string $op, mixed $range = null) 查询日期和时间
 * @method static Query whereBetweenTime(string $field, mixed $startTime, mixed $endTime) 查询日期或者时间范围
 * @method static Query whereBetweenTimeField(string $startField, string $endField) 查询当前时间在两个时间字段范围
 * @method static Query whereYear(string $field, string $year = 'this year') 查询某年
 * @method static Query whereMonth(string $field, string $month = 'this month') 查询某月
 * @method static Query whereDay(string $field, string $day = 'today') 查询某日
 * @method static Query whereRaw(string $where, array $bind = []) 表达式查询
 * @method static Query whereExp(string $field, string $condition, array $bind = []) 字段表达式查询
 * @method static Query when(mixed $condition, mixed $query, mixed $otherwise = null) 条件查询
 * @method static Query join(mixed $join, mixed $condition = null, string $type = 'INNER') JOIN查询
 * @method static Query view(mixed $join, mixed $field = null, mixed $on = null, string $type = 'INNER') 视图查询
 * @method static Query with(mixed $with) 关联预载入
 * @method static Query count(string $field) Count统计查询
 * @method static Query min(string $field) Min统计查询
 * @method static Query max(string $field) Max统计查询
 * @method static Query sum(string $field) SUM统计查询
 * @method static Query avg(string $field) Avg统计查询
 * @method static Query field(mixed $field, boolean $except = false) 指定查询字段
 * @method static Query fieldRaw(string $field, array $bind = []) 指定查询字段
 * @method static Query union(mixed $union, boolean $all = false) UNION查询
 * @method static Query limit(mixed $offset, integer $length = null) 查询LIMIT
 * @method static Query order(mixed $field, string $order = null) 查询ORDER
 * @method static Query orderRaw(string $field, array $bind = []) 查询ORDER
 * @method static Query cache(mixed $key = null, integer $expire = null) 设置查询缓存
 * @method mixed value(string $field) 获取某个字段的值
 * @method array column(string $field, string $key = '') 获取某个列的值
 * @method Model find(mixed $data = null) 查询单个记录 不存在返回Null
 * @method Model findOrEmpty(mixed $data = null) 查询单个记录 不存在返回空模型
 * @method Collection select(mixed $data = null) 查询多个记录
 * @method Model withAttr(array $name, \Closure $closure) 动态定义获取器
 */
class Model extends \think\Model
{

}
