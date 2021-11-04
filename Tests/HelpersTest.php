<?php
namespace Zwei\Context\Tests;


use PHPUnit\Framework\TestCase;
use Zwei\Context\EmptyCtx;
use function Zwei\Context\Background;
use function Zwei\Context\Todo;
use function Zwei\Context\WithValue;

class HelpersTest extends TestCase
{
    public function testBackground()
    {
        $this->assertEquals(Background(), Background());
        $this->assertTrue(Background() instanceof EmptyCtx);
        $this->assertEquals("context.Background", Background()->String());
    }

    public function testTodo()
    {
        $this->assertEquals(Todo(), Todo());
        $this->assertTrue(Todo() instanceof EmptyCtx);
        $this->assertEquals("context.TODO", Todo()->String());
    }

    public function testWithValue() {
        $ctx1 = WithValue(Background(), "key1", "valueV1");
        $ctx2 = WithValue($ctx1, "key1", "valueV2");
        $this->assertEquals("valueV1", $ctx1->Value("key1"));
        $this->assertEquals("valueV2", $ctx2->Value("key1"));
    }

    public function testBackgroundValue() {
        $ctx1 = Background();
        $this->assertEquals(null, $ctx1->Value("key1"));
    }
}