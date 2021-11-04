<?php
include_once __DIR__.'/../vendor/autoload.php';

use function Zwei\Context\WithValue;
use function Zwei\Context\Background;


$ctx1 = WithValue(Background(), "key1", "valueV1");
$ctx2 = WithValue($ctx1, "key1", "valueV2");
var_dump($ctx1->Value("key1"));//输出：valueV1
var_dump($ctx2->Value("key1"));//输出：valueV2