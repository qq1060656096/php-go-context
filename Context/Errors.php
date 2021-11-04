<?php
namespace Zwei\Context;


class Errors
{
    /**
     * @param \Exception $err
     * @return bool
     */
    public static function IsCanceled(\Exception $err) {
        if ($err === Canceled()) {
            return true;
        }
        return false;
    }

    /**
     * @param \Exception $err
     * @return bool
     */
    public static function IsDeadlineExceeded(\Exception $err) {
        if ($err === DeadlineExceeded()) {
            return true;
        }
        return false;
    }
}