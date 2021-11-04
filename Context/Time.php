<?php
namespace Zwei\Context;


class Time
{
    const Microsecond = 1;
    const Millisecond = self::Microsecond * 1000;
    const Second = self::Microsecond * 1000;
    /**
     * @var int 微妙时间戳
     */
    protected $usec = 0;


    public function __construct()
    {
        $this->usec = static::nowMicrosecond();
    }

    /**
     * @param Time $time
     * @return bool
     */
    public function Before(Time $u)
    {
        if ($this->usec < $u->usec) {
            return true;
        }
        return false;
    }

    public function Add($d) {
        $this->usec += $d;
        return $this->usec;
    }
    /**
     * 持续微妙数
     *
     * @param Time $u
     * @return int
     */
    public function Sub(Time $u)
    {
        return static::subRaw($this, $u);
    }

    /**
     * 持续微妙数
     * @param Time $t
     * @param Time $u
     * @return float
     */
    protected static function subRaw(Time $t, Time $u) {
        return $t->usec - $u->usec;
    }


    /**
     * 持续微妙数
     *
     * @param Time $time
     * @return int
     */
    public function Until(Time $time)
    {
        return $time->Sub($this->Now());
    }

    /**
     * 返回当前 Unix 毫秒数时间戳
     * @return float
     */
    public static function  nowMillisecond() {
        return round(self::nowMicrosecond()/ self::Millisecond, 0);
    }

    /**
     * 返回当前 Unix 微秒数时间戳
     * @return float
     */
    public static function nowMicrosecond() {
        list($usec, $sec) = explode(" ", microtime());
        $microsecond = ((float)$usec + (float)$sec) * self::Second;
        $microsecond = round($microsecond, 0);
        return $microsecond;
    }

    /**
     * @return Time
     */
    public static function Now()
    {
        $t = static::newTime(static::nowMicrosecond());
        return $t;
    }

    /**
     * @param null|string $str
     * @return false|float
     */
    public static function strToMicrosecond($str = null) {
        $usec = 0;
        if (is_null($str)) {
            $usec = static::nowMicrosecond();
        } else {
            $usec = strtotime($str);
            if ($usec === false) {
                return false;
            }
            $usec = round(floatval($usec) * 1000 * 1000, 0);
        }
        return $usec;
    }

    /**
     * @param float $usec 微妙数
     * @return static
     */
    public static function newTime($usec) {
        $t = new static();
        $t->usec = $usec;
        return $t;
    }
}