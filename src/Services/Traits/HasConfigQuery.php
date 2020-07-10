<?php

namespace Modules\Core\Services\Traits;

use Carbon\Carbon;
use InvalidArgumentException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Exceptions\DataExistsException;
use Modules\Core\Exceptions\DataNotFoundException;

trait HasConfigQuery
{
    private $config;

    /**
     * @param array $options
     *
     * @return array|Collection
     */
    protected function config(array $options = [])
    {
        if ($this->config == null || ($options['force'] ?? false)) {
            $this->config = collect(config($this->key, []));
        }

        if ($options['collection'] ?? true) {
            return $this->config;
        }

        return $this->config->all();
    }

    protected function refreshConfig()
    {
        $this->config = null;
    }

    protected function storeConfig($data, array $options = [])
    {
        store_config($this->key, $data, $options);
        $this->refreshConfig();
    }


    /**
     * @param Collection $data
     * @param array $options
     *
     * @return Collection
     */
    protected function withCollectionOptions(Collection $data, array $options)
    {
        if ($where = $options['where'] ?? false) {
            foreach ((array) $where as $key => $value) {
                $data = $data->where($key, $value);
            }
        }

        if ($whereIn = $options['whereIn'] ?? false) {
            foreach((array) $whereIn as $key => $values) {
                $data = $data->whereIn($key, $values);
            }
        }

        if ($sortBy = $options['sortBy'] ?? false) {
            $data = call_user_func_array([$data, 'sortBy'], ! is_array($sortBy) ? [$sortBy] : $sortBy);
        }

        if ($callback = $options['collectionCallback'] ?? false) {
            $data = $callback($data);
        }

        return $data;
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return array
     */
    public function all($where = null, array $options = [])
    {
        $config = $this->config();

        if ($options['paginate'] ?? false) {
            return $this->paginate($where, $options);
        }

        return $this->withCollectionOptions($config, array_merge($options, [
            'where' => $where,
        ]))->all();
    }

    /**
     * @param null $where
     * @param array $options
     *
     * @return mixed
     */
    public function one($where = null, array $options = [])
    {
        $config = $this->config();

        $data = $this->withCollectionOptions($config, array_merge($options, [
            'where' => $where,
        ]))->first();

        if (!$data) {
            // @param \Closure|bool $exception 自定义异常设置
            $exception = $options['exception'] ?? true;

            if ($exception) {
                throw is_callable($exception) ? $exception() : new DataExistsException(trans('指定数据未找到'));
            }
        }

        return $data;
    }

    public function count($where = null, array $options = [])
    {
        $config = $this->config();

        return $this->withCollectionOptions($config, array_merge($options, [
            'where' => $where,
        ]))->count();
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return int
     */
    public function has($where = null, array $options = [])
    {
        $config = $this->config();

        if ((is_array($where) && Arr::isAssoc($where)) || is_callable($where)) { // where查询或者回调方法查询
            return $this->withCollectionOptions($config, array_merge($options, [
                    'where' => $where,
                ]))->count() > 0;
        }

        return $config->has($where); // key 或者 [key, key1] 查询
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($where = null, array $options = [])
    {
        $limit = $options['limit'] ?? request('limit', 15);
        $pageName = $options['pageName'] ?? 'page';
        $page = $options['page'] ?? null;

        $maxLimit = $options['maxLimit'] ?? config('core::system.paginate.maxLimit', 100);
        if ($limit > $maxLimit) {
            $limit = $maxLimit;
        }

        $collection = $this->withCollectionOptions($where, $options);

        return app()->makeWith(LengthAwarePaginator::class, [
            'items' => $collection->forPage($page, $limit),
            'count' => $collection->count(),
            'perPage' => $limit,
            'currentPage' => $page,
            'options' => [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        ]);
    }

    /**
     * @param string $key
     * @param array $options
     *
     * @return array
     */
    public function getByKey($key, array $options = [])
    {
        return $this->one(['key' => $key], $options);
    }

    /**
     * @param int $id
     * @param array $options
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteByKey($key, array $options = [])
    {
        $config = $this->config()->filter(function($item) use ($key) {
            return $item['key'] !== $key;
        });

        $this->storeConfig($config);

        return true;
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return bool
     * @throws DataExistsException
     */
    public function create(array $data, array $options = [])
    {
        ['key' => $key] = $data;
        if (empty($key)) {
            throw new InvalidArgumentException(trans('Key键值必须设置'));
        }
        if ($this->has(['key' => $key])) {
            // @param \Closure|bool $exception 自定义异常设置
            $exception = $options['exception'] ?? true;

            if ($exception) {
                throw is_callable($exception) ? $exception($data) : new DataExistsException(trans('数据已存在'));
            }
        }

        $config = $this->config();

        $config->push(array_merge($data, [
            'key' => $key,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]));

        $this->storeConfig($config);

        return true;
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return bool
     * @throws DataNotFoundException
     */
    public function update(array $data, array $options = [])
    {
        ['key' => $key] = $data;
        if (empty($key)) {
            throw new InvalidArgumentException(trans('Key键值必须设置'));
        }
        if (!$this->has(['key' => $key])) {
            // @param \Closure|bool $exception 自定义异常设置
            $exception = $options['exception'] ?? true;

            if ($exception) {
                throw is_callable($exception) ? $exception($data) : new DataNotFoundException(trans('指定数据未找到'));
            }
        }

        $config = $this->config()->map(function($oldData) use ($key, $data) {
            if ($oldData['key'] == $key) {
                $oldData = array_merge($oldData, $data, [
                    'key' => $oldData['key'],
                    'created_at' => $oldData['created_at'] ?? Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            return $oldData;
        });

        $this->storeConfig($config);

        return true;
    }
}
