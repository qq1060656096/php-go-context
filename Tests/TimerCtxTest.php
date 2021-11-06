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
    public function testWithTimoutOnlyOneChildrenCancel() {
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


    /**
     * 只取消 child context
     */
    public function testWithTimoutOneChildrenCancel1() {
        $ctx0 = Background();
        /* @var TimerCtx $ctx1 */
        list($ctx1, $cancelFunc1) = WithTimeout($ctx0, Time::Second * 1);
        $ctx2Value = WithValue($ctx1, "ctx2Value", "ctx2Value.data");
        /* @var CancelCtx $ctx3 */
        list($ctx3, $cancelFunc3)  = WithCancel($ctx2Value);
        /* @var CancelCtx $ctx31 */
        list($ctx31, $cancelFunc31)  = WithCancel($ctx3);
        /* @var TimerCtx $ctx1 */
        list($ctx4, $cancelFunc4) = WithTimeout($ctx31, Time::Second * 1);

        $ctx5Value = WithValue($ctx4, "ctx2Value", "ctx2Value.data");
        /* @var CancelCtx $ctx6 */
        list($ctx6, $cancelFunc6)  = WithCancel($ctx5Value);
        /* @var CancelCtx $ctx7 */
        list($ctx7, $cancelFunc7)  = WithCancel($ctx6);

        $this->assertFalse($ctx1->Done(), '$ctx1->Done()');
        $this->assertFalse($ctx2Value->Done(), '$ctx2Value->Done()');
        $this->assertFalse($ctx3->Done(), '$ctx3->Done()');
        $this->assertFalse($ctx31->Done(), '$ctx31->Done()');

        $cancelFunc1();
        $this->assertTrue($ctx1->Done(), '$ctx1->Done()');
        $this->assertFalse($ctx2Value->Done(), '$ctx2Value->Done()');
        $this->assertTrue($ctx3->Done(), '$ctx3->Done()');
        $this->assertTrue($ctx31->Done(), '$ctx31->Done()');
        $this->assertTrue($ctx4->Done(), '$ctx4->Done()');
        $this->assertFalse($ctx5Value->Done(), '$ctx5Value->Done()');
        $this->assertTrue($ctx6->Done(), '$ctx6->Done()');
        $this->assertTrue($ctx7->Done(), '$ctx7->Done()');

    }

    /**
     * 只取消 child context
     */
    public function testWithTimoutChildrenCancel2() {
        $ctx0 = Background();
        /* @var TimerCtx $ctx1 */
        list($ctx1, $cancelFunc1) = WithTimeout($ctx0, Time::Second * 1);
        $ctx2Value = WithValue($ctx1, "ctx2Value", "ctx2Value.data");
        /* @var CancelCtx $ctx3 */
        list($ctx3, $cancelFunc3)  = WithCancel($ctx2Value);
        /* @var CancelCtx $ctx31 */
        list($ctx31, $cancelFunc31)  = WithCancel($ctx3);
        /* @var TimerCtx $ctx1 */
        list($ctx4, $cancelFunc4) = WithTimeout($ctx31, Time::Second * 1);

        $ctx5Value = WithValue($ctx4, "ctx2Value", "ctx2Value.data");
        /* @var CancelCtx $ctx6 */
        list($ctx6, $cancelFunc6)  = WithCancel($ctx5Value);
        /* @var CancelCtx $ctx7 */
        list($ctx7, $cancelFunc7)  = WithCancel($ctx6);

        $this->assertFalse($ctx1->Done(), '$ctx1->Done()');
        $this->assertFalse($ctx2Value->Done(), '$ctx2Value->Done()');
        $this->assertFalse($ctx3->Done(), '$ctx3->Done()');
        $this->assertFalse($ctx31->Done(), '$ctx31->Done()');

        $cancelFunc4();
        $this->assertFalse($ctx1->Done(), '$ctx1->Done()');
        $this->assertFalse($ctx2Value->Done(), '$ctx2Value->Done()');
        $this->assertFalse($ctx3->Done(), '$ctx3->Done()');
        $this->assertFalse($ctx31->Done(), '$ctx31->Done()');
        $this->assertTrue($ctx4->Done(), '$ctx4->Done()');
        $this->assertFalse($ctx5Value->Done(), '$ctx5Value->Done()');
        $this->assertTrue($ctx6->Done(), '$ctx6->Done()');
        $this->assertTrue($ctx7->Done(), '$ctx7->Done()');

    }

}