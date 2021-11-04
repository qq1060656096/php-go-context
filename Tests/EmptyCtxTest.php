<?php
namespace Zwei\Context\Tests;


use PHPUnit\Framework\TestCase;
use Zwei\Context\EmptyCtx;

class EmptyCtxTest extends TestCase
{
    public function testValue() {
        $ctx = new EmptyCtx();
        $this->assertEquals(null, $ctx->Value("any key"));
    }

    public function testString() {
        $ctx = new EmptyCtx();
        $this->assertEquals("unknown empty Context", $ctx->String());
    }
}