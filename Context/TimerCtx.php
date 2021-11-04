<?php
namespace Zwei\Context;


class TimerCtx extends CancelCtx
{
    /**
     * @var CancelCtx
     */
    protected $cancelCtx;

    /**
     * @var Time
     */
    protected $deadline;

    public function String()
    {
        return (string) $this . ".WithDeadline(" +
            $this->deadline.String() + " [" +
            $this->deadline->Until($this->deadline).String() + "])";
    }


    public function Deadline()
    {
        $dur = $this->deadline->Until($this->deadline);
        if ( $dur <= 0) {
            $this->err = DeadlineExceeded();
            $this->done = true;
        }
        return [$this->deadline, true];
    }

    /**
     * @inheritDoc
     */
    public function Cancel($removeFromParent, \Exception $err)
    {
        $this->cancelCtx->Cancel($removeFromParent, $err);
        if ($removeFromParent) {
            removeChild($this->cancelCtx->parent, $this);
        }
    }

    public static function newTimerCtx(Context $parent, Time $d)
    {
        $ctx = new static();
        $ctx->cancelCtx = self::newCancelCtx($parent);
        $ctx->deadline = $d;
        return $ctx;
    }
}
