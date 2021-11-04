<?php
namespace Zwei\Context;


class Time
{
    /**
     * @param Time $time
     * @return int
     */
    public function Before(Time $time)
    {
        return 0;
    }

    /**
     * @param Time $time
     * @return int
     */
    public function Sub(Time $time)
    {
        return 0;
    }

    /**
     * @return Time
     */
    public function Now()
    {
        return new Time();
    }

    public function Until(Time $time)
    {
        return $time->Sub($this->Now());
    }
}