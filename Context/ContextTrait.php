<?php
namespace Zwei\Context;


trait ContextTrait
{
    /**
     * @var Context
     */
    protected $parent;

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
}