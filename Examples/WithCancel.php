<?php
# 示例文件路径：Examples/WithCancel.php
include_once __DIR__.'/../vendor/autoload.php';

use function Zwei\Context\WithCancel;
use function Zwei\Context\Background;
use function Zwei\Context\Canceled;
use Zwei\Context\Context;
use Zwei\Context\CancelCtx;
use Zwei\Context\Errors;
$ctx = Background();
/* @var CancelCtx $ctx1*/
list($ctx1, $cancelFunc) = WithCancel($ctx);
try {
    rpc1($ctx1);
    rpc2($ctx1);
    Business($ctx1);
    $cancelFunc();// 取消 CancelCtx
    echo "手动取消\n";
} catch (Exception $e) {
    $cancelFunc();
    echo "异常取消\n";
    throw $e;
}
var_dump($ctx1->Err() === Canceled(), Errors::IsCanceled($ctx1->Err()));//结果为： true, true


function Business(Context $ctx) {
    if ($ctx->Done()) {
        return;
    }
    // 业务操作
    Dao($ctx);
    echo "done: ", __METHOD__, "\n";
}

function Dao(Context $ctx) {
    if ($ctx->Done()) {
        return;
    }
    echo "done: ", __METHOD__, "\n";
}

function rpc1(Context $ctx) {
    if ($ctx->Done()) {
        return;
    }
    // 业务操作
    echo "done: ", __METHOD__, "\n";
}

function rpc2(Context $ctx) {
    if ($ctx->Done()) {
        return;
    }
    // 业务操作
    echo "done: ", __METHOD__, "\n";
}
