<?php


namespace Zwei\Context;


interface Canceler
{
    /**
     * @param bool $removeFromParent
     * @param \Exception $err
     * @return mixed
     */
    public function Cancel($removeFromParent, \Exception $err);
    public function Done();
}