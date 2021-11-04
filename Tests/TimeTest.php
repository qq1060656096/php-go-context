<?php
namespace Zwei\Context\Tests;


use PHPUnit\Framework\TestCase;
use Zwei\Context\Time;

class TimeTest extends TestCase
{
    public function testUnitBefore() {
        $d = Time::newTime(Time::strToMicrosecond("2038-01-19 03:14:07"));
        $t = new Time();
        $dur = $t->Until($d);
        $this->assertTrue($dur > 0);
        $this->assertTrue($t->Before($d));
    }

    public function testAdd() {
        $t = new Time();
        $oldT = clone $t;
        $t->Add(Time::Millisecond);
        $this->assertEquals($oldT->Add(0) + Time::Millisecond, $t->Add(0));
    }
}