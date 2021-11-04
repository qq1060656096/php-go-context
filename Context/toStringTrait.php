<?php
namespace Zwei\Context;


trait toStringTrait
{
    public function __toString()
    {
        if (method_exists($this, 'String')) {
            return $this->String();
        }
        return string($this);
    }
}