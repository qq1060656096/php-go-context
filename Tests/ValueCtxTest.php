<?php
namespace Zwei\Context\Tests;


use PHPUnit\Framework\TestCase;
use Zwei\Context\ValueCtx;
use function Zwei\Context\Background;
use function Zwei\Context\WithValue;

class ValueCtxTest extends TestCase
{
    public function testWithValue() {
        $ctx1 = ValueCtx::WithValue(Background(), "key1", "valueV1");
        $ctx2 = ValueCtx::WithValue(Background(), "key1", "valueV2");
        $this->assertEquals("valueV1", $ctx1->Value("key1"));
        $this->assertEquals("valueV2", $ctx2->Value("key1"));
    }
}