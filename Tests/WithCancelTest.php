<?php
namespace Zwei\Context\Tests;


use PHPUnit\Framework\TestCase;
use Zwei\Context\CancelCtx;
use function Zwei\Context\Background;
use function Zwei\Context\WithCancel;
use function Zwei\Context\WithValue;

class WithCancelTest extends TestCase
{
    public function testWithCancel() {
        $ctx0 = Background();
        /* @var CancelCtx $ctx1 */
        list($ctx1, $cancelFunc) = WithCancel($ctx0);
        $this->assertFalse($ctx1->Done());
        $cancelFunc();
        $this->assertTrue($ctx1->Done());
    }

    public function testWithCancelChildrenCancel() {
        $ctx0 = Background();
        /* @var CancelCtx $ctx1 */
        list($ctx1, $cancelFunc1) = WithCancel($ctx0);
        $ctx2Value = WithValue($ctx1, "ctx2Value", "ctx2Value.data");
        /* @var CancelCtx $ctx3 */
        list($ctx3, $cancelFunc3)  = WithCancel($ctx2Value);
        list($ctx31, $cancelFunc31)  = WithCancel($ctx2Value);

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
     * 只取消一个 child context
     */
    public function testWithCancelOnlyOneChildrenCancel() {
        $ctx0 = Background();
        /* @var CancelCtx $ctx1 */
        list($ctx1, $cancelFunc1) = WithCancel($ctx0);
        $ctx2Value = WithValue($ctx1, "ctx2Value", "ctx2Value.data");
        /* @var CancelCtx $ctx3 */
        list($ctx3, $cancelFunc3)  = WithCancel($ctx2Value);
        list($ctx31, $cancelFunc31)  = WithCancel($ctx2Value);

        $this->assertFalse($ctx1->Done(), '$ctx1->Done()');
        $this->assertFalse($ctx2Value->Done(), '$ctx2Value->Done()');
        $this->assertFalse($ctx3->Done(), '$ctx3->Done()');
        $this->assertFalse($ctx31->Done(), '$ctx31->Done()');

        $cancelFunc3();
        $this->assertFalse($ctx1->Done(), '$ctx1->Done()');
        $this->assertFalse($ctx2Value->Done(), '$ctx2Value->Done()');
        $this->assertTrue($ctx3->Done(), '$ctx3->Done()');
        $this->assertFalse($ctx31->Done(), '$ctx31->Done()');
    }
}