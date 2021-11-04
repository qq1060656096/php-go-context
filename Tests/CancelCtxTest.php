<?php
namespace Zwei\Context\Tests;


use PHPUnit\Framework\TestCase;
use Zwei\Context\CancelCtx;
use function Zwei\Context\cancelCtxKey;
use function Zwei\Context\Canceled;

class CancelCtxTest extends TestCase
{
    public function testValue() {
        $ctx = new CancelCtx();
        $this->assertEquals($ctx, $ctx->Value(cancelCtxKey()));
        $this->assertTrue($ctx === $ctx->Value(cancelCtxKey()));
    }

    public function testCancel()
    {
        $ctx = new CancelCtx();
        $ctx->Cancel(false, Canceled());
        $this->assertTrue($ctx->Done());
        $this->assertTrue($ctx->Err() === Canceled());
    }
}