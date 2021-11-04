<?php
namespace Zwei\Context;


class EmptyCtx implements Context
{
    use toStringTrait;

    /**
     * @inheritDoc
     */
    public function Deadline()
    {
        return [0, false];
    }

    /**
     * @inheritDoc
     */
    public function Done()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function Err()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function Value($key)
    {
        return null;
    }

    public function String()
    {
        if ($this === Background()) {
            return "context.Background";
        }

        if ($this === TODO()) {
            return "context.TODO";
        }

        return "unknown empty Context";
    }

}