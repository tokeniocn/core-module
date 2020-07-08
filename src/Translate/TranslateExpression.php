<?php
namespace Modules\Core\Translate;

class TranslateExpression
{
    /**
     * @var string
     */
    protected $key;
    /**
     * @var array
     */
    protected $params;

    public function __construct(string $key, $params = [])
    {
        $this->key = $key;
        $this->params = $params;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public static function warp($data)
    {
        if (!$data instanceof TranslateExpression) {
            if (isset($data['key'])) {
                $data = new static($data['key'], $data['params'] ?? []);
            } else {
                $data = new static($data);
            }
        }

        return $data;
    }
}
