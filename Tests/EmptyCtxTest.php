<?php
namespace Zwei\Context\Tests;


use PHPUnit\Framework\TestCase;
use Zwei\Context\EmptyCtx;
use function Zwei\Context\Background;

class EmptyCtxTest extends TestCase
{
    public function testValue() {
        $ctx = new EmptyCtx();
        $this->assertEquals(null, $ctx->Value("any key"));
    }

    public function testString() {
        $ctx = new EmptyCtx();
        $this->assertEquals("unknown empty Context", (string)$ctx);
    }

    public function testString2() {
        $ctx = Background();
        $this->assertEquals($ctx->String(), (string)$ctx);
    }
}