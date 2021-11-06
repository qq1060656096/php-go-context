<?php
namespace Zwei\Context\Tests;


use PHPUnit\Framework\TestCase;
use Zwei\Context\CancelCtx;
use Zwei\Context\Errors;
use Zwei\Context\Time;
use Zwei\Context\TimerCtx;
use function Zwei\Context\Background;
use function Zwei\Context\Canceled;
use function Zwei\Context\WithCancel;
use function Zwei\Context\WithTimeout;
use function Zwei\Context\WithValue;

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

    public function testCancelFunc() {
        $ctx0 = Background();
        /* @var TimerCtx $ctx1 */
        list($ctx1, $cancelFunc1) = WithTimeout($ctx0, Time::Second * 1);
        $this->assertFalse($ctx1->Done());
        $cancelFunc1();
        $this->assertTrue(Errors::IsCanceled($ctx1->Err()));
        $this->assertTrue($ctx1->Done());
    }

    /**
     * 只取消一个 child context
     */
    public function testWithCancelOnlyOneChildrenCancel() {
        $ctx0 = Background();
        /* @var TimerCtx $ctx1 */
        list($ctx1, $cancelFunc1) = WithTimeout($ctx0, Time::Second * 1);
        $ctx2Value = WithValue($ctx1, "ctx2Value", "ctx2Value.data");
        /* @var CancelCtx $ctx3 */
        list($ctx3, $cancelFunc3)  = WithCancel($ctx2Value);
        /* @var CancelCtx $ctx31 */
        list($ctx31, $cancelFunc31)  = WithCancel($ctx3);


        $this->assertFalse($ctx1->Done(), '$ctx1->Done()');
        $this->assertFalse($ctx2Value->Done(), '$ctx2Value->Done()');
        $this->assertFalse($ctx3->Done(), '$ctx3->Done()');
        $this->assertFalse($ctx31->Done(), '$ctx31->Done()');

        $cancelFunc1();
        $this->assertTrue($ctx1->Done(), '$ctx1->Done()');
        $this->assertFalse($ctx2Value->Done(), '$ctx2Value->Done()');
        $this->assertTrue($ctx3->Done(), '$ctx3->Done()');
        $this->assertTrue($ctx31->Done(), '$ctx31->Done()');

    }
}