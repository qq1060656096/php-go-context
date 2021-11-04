<?php
namespace Zwei\Context\Tests;


use PHPUnit\Framework\TestCase;
use Zwei\Context\CancelCtx;
use Zwei\Context\Errors;
use Zwei\Context\Time;
use Zwei\Context\TimerCtx;
use function Zwei\Context\Background;
use function Zwei\Context\WithTimeout;

class WithTimeoutTest extends TestCase
{
    public function testWithTimeout() {
        $ctx0 = Background();
        /* @var TimerCtx $ctx1 */
        list($ctx1, $cancelFunc) = WithTimeout($ctx0, Time::Second * 1);
        sleep(1);
        $ctx1->Deadline();
        $this->assertTrue(Errors::IsDeadlineExceeded($ctx1->Err()));
    }

    public function testWithTimeoutReturnWithCancel() {
        $ctx0 = Background();
        /* @var TimerCtx $ctx1 */
        list($ctx1, $cancelFunc) = WithTimeout($ctx0, Time::Second * 1);
        sleep(1);
        $ctx1->Deadline();
        $this->assertTrue(Errors::IsDeadlineExceeded($ctx1->Err()));
        list($ctx2, $cancelFunc2) = WithTimeout($ctx1, Time::Second * 1);
        $ctx1->Deadline();
        $this->assertEquals(CancelCtx::class, get_class($ctx2));
    }
}