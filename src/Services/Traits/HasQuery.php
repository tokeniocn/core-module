<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Exceptions\ModelSaveException;

/**
 * Service需要设置model参数
 *
 * 可以在__construct中初始化model, 示例:
 *
 * protected $model;
 *
 * public function __construct(User $model)
 * {
 *     $this->model = $model;
 * }
 *
 * @package Modules\Core\Services\Traits
 */
trait HasQuery
{
    /**
     * 类使用需在__construct()中定义model
     *
     * @var Illuminate\Database\Eloquent\Model
     */
//    protected $model;

    /**
     * @return Builder
     */
    public function query(array $options = []): Builder
    {
        $query = $this->model->newQuery();

        if (empty($options)) {
            return $query;
        }

        return $this->withQueryOptions($query, $options);
    }

    /**
     * @param $query
     * @param array $options
     */
    protected function withQueryOptions(Builder $query, array $options): Builder
    {
        if ($where = $options['where'] ?? false) {
            $query->where($where);
        }

        if ($with = $options['with'] ?? false) {
            $query->with($with);
        }

        if ($orderBy = $options['orderBy'] ?? false) {
            call_user_func_array([$query, 'orderBy'], ! is_array($orderBy) ? [$orderBy] : $orderBy);
        }

        if ($callback = $options['queryCallback'] ?? false) {
            $callback($query);
        }

        return $query;
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return Model|mixed
     */
    public function one($where = null, array $options = [])
    {
        $model = $this->query(array_merge($options, ['where' => $where]))->first();

        if (!$model) {
            // @param \Closure|bool $exception 自定义异常设置
            $exception = $options['exception'] ?? true;

            if ($exception) {
                throw is_callable($exception) ? $exception() : new ModelNotFoundException(trans('指定数据未找到'));
            }
        }

        return $model;
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all($where = null, array $options = [])
    {
        if ($options['paginate'] ?? false) {
            return $this->paginate($where, $options);
        }

        return $this->query(array_merge($options, ['where' => $where]))->get();
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return int
     */
    public function count($where = null, array $options = [])
    {
        return $this->query(array_merge($options, ['where' => $where]))->count();
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return int
     */
    public function has($where = null, array $options = [])
    {
        return $this->query(array_merge($options, ['where' => $where]))->exists();
    }

    /**
     * @param $column
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return mixed
     */
    public function min($column, $where = null, array $options = [])
    {
        return $this->query(array_merge($options, ['where' => $where]))->min($column);
    }

    /**
     * @param $column
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return mixed
     */
    public function max($column, $where = null, array $options = [])
    {
        return $this->query(array_merge($options, ['where' => $where]))->max($column);
    }

    /**
     * @param $column
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return mixed
     */
    public function sum($column, $where = null, array $options = [])
    {
        return $this->query(array_merge($options, ['where' => $where]))->sum($column);
    }

    /**
     * @param $column
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return mixed
     */
    public function avg($column, $where = null, array $options = [])
    {
        return $this->query(array_merge($options, ['where' => $where]))->avg($column);
    }

    /**
     * @param int $limit
     * @param array $columns
     * @param string $pageName
     * @param null $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($where = null, array $options = [])
    {
        $limit = $options['limit'] ?? request('limit', 15);
        $pageName = $options['pageName'] ?? 'page';
        $columns = $options['columns'] ?? ['*'];
        $page = $options['page'] ?? null;

        $maxLimit = $options['maxLimit'] ?? config('core::system.paginate_maxLimit', 100);
        if ($limit > $maxLimit) {
            $limit = $maxLimit;
        }

        return $this->query(array_merge($options, ['where' => $where]))
                    ->paginate($limit, $columns, $pageName, $page);
    }

    /**
     * @param int $id
     * @param array $options
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteById($id, array $options = [])
    {
        return $this->getById($id, $options)->delete();
    }

    /**
     * @param int $id
     * @param array $options
     *
     * @return Model
     */
    public function getById($id, array $options = [])
    {
        return $this->one(['id' => $id], $options);
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return bool|Model
     */
    public function create(array $data, array $options = [])
    {
        $model = $this->query()->newModelInstance($data);

        $beforeSave = $options['beforeSave'] ?? false;

        if (is_callable($beforeSave)) {
            $beforeSave($model);
        }

        if (!$model->save()) {
            // @param \Closure|bool $exception 自定义异常设置
            $exception = $options['exception'] ?? true;

            if ($exception) {
                throw is_callable($exception) ? $exception($model) : ModelSaveException::withModel($model);
            }

            return false;
        }

        return $model;
    }
}
