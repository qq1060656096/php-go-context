<?php
namespace Zwei\Context;

/**
 * Interface Context
 * @package Zwei\Context
 */
interface Context {
    /**
     * @return [$deadline timestamp, ok bool]
     */
    public function Deadline();

    /**
     * @return bool
     */
    public function Done();

    /**
     * @return \Exception
     */
    public function Err();

    /**
     * @param string $key
     * @return mixed
     */
    public function Value($key);
}