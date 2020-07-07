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
}
