<?php

namespace Modules\Core\Services\Frontend;

use UnexpectedValueException;
use Modules\Core\Models\ListData;
use Modules\Core\Services\Traits\HasListData;
use Illuminate\Support\Str;

class AnnounceService
{
    use HasListData {
        all as queryAll;
    }

    /**
     * @var ListData
     */
    protected $model;

    /**
     * @var string
     */
    protected $type = 'announce';

    public function __construct(ListData $model)
    {
        $this->model = $model;
    }


    public function store(array $data)
    {
        return $this->model::create([
            'key' => $this->generateUniqueKey(),
            'type' => $this->type,
            'value' => [
                'title' => $data['title'],
                'content' => $data['content']
            ],
            'module' => '*'
        ]);
    }

    public function update(string $key, array $data, $options = [])
    {
        $announce = $this->getByKey($key);
        $announce->value = [
            'title' => $data['title'],
            'content' => $data['content']
        ];
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

    public function all($where = null, array $options = [])
    {
        $announceList = $this->queryAll($where, $options);
        $announceList = $announceList->toArray();
        $announceList['data'] = $this->normalizeAnnounce($announceList['data']);
        return $announceList;
    }

    public function normalizeAnnounce(array $data)
    {
        return array_map(function ($item) {
//            $item = array_merge($item, $item['value']);
            $item['title'] = $item['value']['title'];
            unset($item['value']);
            return $item;
        }, $data);
    }
}
