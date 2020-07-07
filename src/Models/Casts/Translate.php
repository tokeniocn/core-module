<?php

namespace Modules\Core\Models\Casts;

use UnexpectedValueException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Modules\Core\Translate\TranslateExpression;

class Translate implements CastsAttributes
{
    /**
     * 将取出的数据进行转换
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $key
     * @param  mixed $value
     * @param  array $attributes
     *
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        if (!is_array($value)) {
            $value = json_decode($value, true);
        }

        if (is_array($value) && isset($value['key'])) {
            return trans($value['key'], $value['params'] ?? []);
        }

        return null;
    }

    /**
     * 转换成将要进行存储的值
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $key
     * @param  array $value
     * @param  array $attributes
     *
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        $return = [
            'key' => '',
            'params' => []
        ];

        if (is_string($value)) {
            $return['key'] = $value;
        } elseif ($value instanceof TranslateExpression) {
            $return['key'] = $value->getKey();
            $return['value'] = $value->getParams();
        } else {
            throw new UnexpectedValueException(
                'The cast of type "translate" value must an instance of ' . TranslateExpression::class . ' or string (trans key).'
            );
        }

        return json_encode($return, JSON_UNESCAPED_UNICODE);
    }
}
