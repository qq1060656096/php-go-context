<?php
namespace Zwei\Context\Tests;


use PHPUnit\Framework\TestCase;
use Zwei\Context\Errors;
use Zwei\Context\Time;
use Zwei\Context\TimerCtx;
use function Zwei\Context\Background;
use function Zwei\Context\WithTimeout;

class TimerCtxTest extends TestCase
{
    public function testTimeout() {
        $ctx0 = Background();
        /* @var TimerCtx $ctx1 */
        list($ctx1, $cancelFunc1) = WithTimeout($ctx0, Time::Second * 1);
        $this->assertFalse($ctx1->Done());
        sleep(1);
        $this->assertTrue($ctx1->Done());
        $this->assertTrue(Errors::IsDeadlineExceeded($ctx1->Err()));
    }

    public function test() {
        $ctx0 = Background();
        /* @var TimerCtx $ctx1 */
        list($ctx1, $cancelFunc1) = WithTimeout($ctx0, Time::Second * 1);
        $cancelFunc1();
        $this->assertTrue(Errors::IsCanceled($ctx1->Err()));
    }

}