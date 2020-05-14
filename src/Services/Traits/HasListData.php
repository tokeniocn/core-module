<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * 轻量级列表数据 保存到list_data中
 *
 * @package Modules\Core\Services\Traits
 */
trait HasListData
{
    use HasQuery {
        withQueryOptions as queryWithQueryOptions;
    }

    /**
     * 默认*表示全局模块可用
     *
     * @var string
     */
    protected $module = '*';

    /**
     * 需自行定义类型
     *
     * @var Illuminate\Database\Eloquent\Model
     */
//    protected $type;

    public function withQueryOptions(Builder $query, array $options): Builder
    {
        // 默认in查询全局模块
        // $options['module'] 查询指定模块
        $module = $options['module'] ?? ($this->module == '*' ? $this->module : ['*', $this->module]);
        $type = $options['type'] ?? $this->type;

        $query->module($module)
              ->type($type);

        return $this->queryWithQueryOptions($query, $options);
    }

    /**
     * @param $key
     * @param array $options
     *
     * @return mixed
     */
    public function getByKey($key, array $options = [])
    {
        return $this->one(['key' => $key], $options);
    }
}
