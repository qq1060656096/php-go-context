<?php
namespace Zwei\Context;


class ValueCtx implements Context
{
    use ContextTrait;

    protected $key;

    protected $value;

    /**
     * @param string $key
     * @return mixed|null
     */
    public function Value($key)
    {
        if ($this->key == $key) {
            return $this->value;
        }
        if ($this->parent) {
            return $this->parent->Value($key);
        }
        return null;
    }

    /**
     * @param Context $parent
     * @param mixed $key
     * @param mixed $value
     * @return ValueCtx
     */
    public static function WithValue(Context $parent, $key, $value) {
        $obj = new ValueCtx();
        $obj->key = $key;
        $obj->value = $value;
        $obj->parent = $parent;
        return $obj;
    }
}