<?php

namespace Modules\Core\Services\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Models\Admin\AdminMenu;
use Modules\Core\Services\Traits\HasQuery;

class AdminMenuService
{
    use HasQuery {
        withQueryOptions as queryWithQueryOptions;
    }

    /**
     * @var AdminMenu
     */
    protected $model;

    public function __construct(AdminMenu $model)
    {
        $this->model = $model;
    }

    /**
     * @param Builder $query
     * @param array $options
     *
     * @return Builder
     */
    public function withQueryOptions(Builder $query, array $options)
    {
        if ($options['whereEnable'] ?? true) { // 默认查询启用状态的菜单
            $query->whereEnabled();
        }

        return $this->queryWithQueryOptions($query, $options);
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getMenuTree(array $options = [])
    {
        return $this->normalizeMenu($this->all(null, $options));
    }

    /**
     * @param Collection $data
     * @param int $parentId
     *
     * @return array
     */
    protected  function normalizeMenu($data, $parentId = 0)
    {
        $menu = [];
        foreach ($data as $item) {
            $a = $item->parent_id;

            if ($item->parent_id == $parentId) {
                $children = $this->normalizeMenu($data, $item->id);

                $menu[] = array_merge($item->toArray(), empty($children) ? [] : ['children' => $children]);
            }
        }

        return $menu;
    }
}
