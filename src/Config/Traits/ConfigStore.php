<?php

namespace Modules\Core\Config\Traits;

use Illuminate\Support\Arr;
use UnexpectedValueException;
use Illuminate\Filesystem\Filesystem;
use InvalidArgumentException;
use Modules\Core\Config\Models\Config;

trait ConfigStore
{
    /**
     * @return string
     */
    public function model()
    {
        return Config::class;
    }

    public function loadSettingsFromCachedFile()
    {
        $path = $this->getSettingsCachedPath();

        if (!file_exists($path)) {
            $this->cacheSettingsToFile();
        }

        $this->set(require $path);
    }

    public function getSettingsCachedPath()
    {
        return storage_path('framework/config.php');
    }

    public function cacheSettingsToFile()
    {
        $modelClass = $this->model();

        $items = [];
        foreach ($modelClass::all() as $setting) {
            $key = $setting->module == '*' ? $setting->key : $setting->module . '::';
            $items[$key] =  array_merge($items[$key] ?? [], [
                $setting->key => $setting->value
            ]);
        }

        $path = $this->getSettingsCachedPath();

        resolve(Filesystem::class)
            ->put($path, '<?php return ' . var_export($items, true) . ';' . PHP_EOL);
    }

    protected function normalizeSchema(array $value)
    {
        if (!Arr::isAssoc($value)) {
            throw new UnexpectedValueException('The config value type must be assoc array.');
        }

        $schema = [];
        $newValue = [];
        foreach ($value as $key => $item) {
            if (is_string($item)) {
                $item = [
                    'value' => $item,
                    'type' => 'text',
                ];
            } elseif (is_array($item) && !array_key_exists('value', $item)) {
                $item = [
                    'value' => $item,
                    'type' => 'json'
                ];
            }
            $item = array_merge([
                'key' => $key,
                'type' => is_string($item['value']) ? 'text' : 'json',
                'value' => '',
                'title' => $key,
                'description' => ''
            ], $item);

            $schema[$key] = $item;
            $newValue[$key] = $item['value'];
        }

        return [
            'value' => $newValue,
            'schema' => $schema
        ];

    }

    /**
     * @param string $key
     * @param mixed $value
     * @param array $options
     */
    public function store($key, $value, array $options = [])
    {
        $data = [$key => $value];
        $modelClass = $this->model();

        foreach ($data as $key => $value) {
            if (strpos($key, '.') !== false) {
                throw new InvalidArgumentException('Config only support store one-level settings(key without ".").');
            }
            if (strpos($key, '::') === false) {
                $key = '*::' . $key;
            }
            list($module, $key) = explode('::', $key);

            /** @var Config $model */
            $model = $modelClass::firstOrNew([
                'key' => $key,
                'module' => $module,
            ]);
            if ($options['schema'] ?? false) {
                [
                    'value' => $value,
                    'schema' => $schema
                ] = $this->normalizeSchema($value);
                $model->schema = $schema;
            }

            $model->value = $value;
            $model->description = $options['description'] ?? '';
            $model->saveOrFail();
        }

        $this->set($data);

        if ($options['refreshCache'] ?? true) {
            $this->cacheSettingsToFile();
        }
    }


}
