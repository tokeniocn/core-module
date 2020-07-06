<?php

namespace Modules\Core\Services\Frontend;

use Modules\Core\Models\Frontend\Announce;
use PascalDeVink\ShortUuid\ShortUuid;
use UnexpectedValueException;
use Modules\Core\Services\Traits\HasListData;
use Illuminate\Support\Str;

class AnnounceService
{
    use HasListData;

    /**
     * @var Announce
     */
    protected $model;

    /**
     * @var string
     */
    protected $type = 'announce';

    public function __construct(Announce $model)
    {
        $this->model = $model;
    }

    public function store(array $data)
    {
        return $this->model::create([
            'key' => ShortUuid::uuid1(),
            'type' => $this->type,
            'value' => $data['value'],
            'module' => '*'
        ]);
    }

    public function update(string $id, array $data, $options = [])
    {
        $announce = $this->getById($id);
        $announce->setTranslations('value', $data['value']);
        $announce->saveIfFail();
        return $announce;
    }


    public function generateUniqueKey(array $options = [])
    {
        $i = 1;
        $max = $options['max'] ?? 10;
        while (true) {
            $key = Str::random(6);
            $keyExists = $this->getByKey($key, ['exception' => false]);

            if (!$keyExists) {
                return $key;
            } elseif ($i > $max) {
                throw new UnexpectedValueException('Max generate announce key times.');
            }
            $i++;
        }
    }

    public function getByKey($key, array $options = [])
    {
        return $this->one(['key' => $key, 'type' => $this->type], $options);
    }
}
