<?php

namespace Modules\Core\Config\Traits;

use InvalidArgumentException;
use Illuminate\Filesystem\Filesystem;
use Modules\Core\Models\Config;
use Illuminate\Support\Facades\Schema;

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

        // detect config table exists when first install
        if (Schema::hasTable($modelClass::table())) {
            foreach ($modelClass::all() as $setting) {
                $key = $setting->module == '*' ? $setting->key : $setting->module . '::';
                $items[$key] =  array_merge($items[$key] ?? [], [
                    $setting->key => $setting->value
                ]);
            }
        }

        $path = $this->getSettingsCachedPath();

        resolve(Filesystem::class)
            ->put($path, '<?php return ' . var_export($items, true) . ';' . PHP_EOL);
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
