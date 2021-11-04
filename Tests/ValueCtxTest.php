<?php
namespace Zwei\Context\Tests;


use PHPUnit\Framework\TestCase;
use Zwei\Context\ValueCtx;
use function Zwei\Context\Background;
use function Zwei\Context\WithValue;

class ValueCtxTest extends TestCase
{
    public function testWithValue() {
        $ctx0 = Background();
        $ctx1 = ValueCtx::WithValue($ctx0, "key1", "valueV1");
        $ctx2 = ValueCtx::WithValue($ctx1, "key1", "valueV2");
        $this->assertEquals("valueV1", $ctx1->Value("key1"));
        $this->assertEquals("valueV2", $ctx2->Value("key1"));
        $this->assertEquals(null, $ctx0->Value("key1"));

    }
}