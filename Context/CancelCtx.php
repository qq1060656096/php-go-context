<?php
namespace Zwei\Context;


class CancelCtx implements Context
{
    use ContextTrait;

    protected $done = false;

    /**
     * @var \Exception|null
     */
    protected $err = null;

    protected $children = [];
    /**
     * @inheritDoc
     */
    public function Value($key)
    {
        if (!$this->parent) {
            return null;
        }
        return $this->parent->Value($key);
    }

    /**
     * @inheritDoc
     */
    public function Done()
    {
        if ($this->done) {
            return true;
        }
        return false;
    }

    /**
     * @return \Exception|null
     */
    public function Err()
    {
        if ($this->err) {
            return $this->err;
        }
        return null;
    }

    public function String() {
        return sprintf("%s:WithCancel", (string)$this);
    }
}