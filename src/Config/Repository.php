<?php

namespace Modules\Core\Config;

use Illuminate\Support\Arr;
use Illuminate\Config\Repository as ConfigRepository;
use Modules\Core\Config\Traits\ConfigStore;

/**
 * Class Repository
 * @package Modules\Core\Config
 */
class Repository extends ConfigRepository
{
    use ConfigStore;

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return parent::has($this->normalizeKey($key));
    }

    /**
     * Get the specified configuration value.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (is_array($key)) {
            return $this->getMany($key);
        }

        return Arr::get($this->items, $this->normalizeKey($key), $default);
    }

    /**
     * Get many configuration values.
     *
     * @param  array  $keys
     * @return array
     */
    public function getMany($keys)
    {
        $config = [];

        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                [$key, $default] = [$default, null];
            }

            $config[$key] = Arr::get($this->items, $this->normalizeKey($key), $default);
        }

        return $config;
    }

    /**
     * Set a given configuration value.
     *
     * @param  array|string  $key
     * @param  mixed  $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            Arr::set($this->items, $this->normalizeKey($key), $value);
        }
    }

    /**
     * support namespace key
     *
     * ```
     * $this->get('test::');
     * $this->get('test::a.b.c');
     * $this->set('test::', []);
     * $this->set('test::a.b.c', 'x');
     * ```
     *
     * @param string $key
     *
     * @return mixed
     */
    public function normalizeKey(string $key)
    {
        if (strpos($key, '::') !== false) {
            return rtrim(str_replace('::', '::.', $key), '.');
        }

        return $key;
    }
}
