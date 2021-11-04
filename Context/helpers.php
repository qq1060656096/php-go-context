<?php
namespace Zwei\Context;

use Zwei\Context\Exception\CancelException;

function Background()
{
    return newEmptyCtx('background');
}

function Todo()
{
    return newEmptyCtx('todo');
}

/**
 * @param Context $parent
 * @param mixed $key
 * @param mixed $value
 * @return ValueCtx
 */
function WithValue(Context $parent, $key, $value) {
    return ValueCtx::WithValue($parent, $key, $value);
}


/**
 * @param string $name
 * @return EmptyCtx
 */
function newEmptyCtx($name)
{
    static $instances = null;
    if (!isset($instances[$name])) {
        $instances[$name] = new EmptyCtx();
    }
    return $instances[$name];
}



/**
 * 返回一个 CancelCtx，当 cancelFunc() 方法被调用时，或者父级 调用了 cancel，所有子节点 Done() 方法都将返回true
 * @return array [Context $ctx,CancelFunc $cancelFunc]
 */
function WithCancel(Context $parent)
{
    $c = newCancelCtx($parent);
    propagateCancel($parent, $c);
    $cancelFunc = function () use($c) {
        var_dump(__LINE__);
        $c->cancel(false, Canceled());
    };
    return [$c, $cancelFunc];
}


function propagateCancel(Context $parent, Canceler $child) {
    // 1. 父 context 取消，就取消子 context
    $done = $parent->Done();
    if ($done) {
        $child->cancel(false, $parent->Err());
        return;
    }
    /* @var CancelCtx $p*/
    /* @var bool $ok */
    list($p, $ok) = parentCancelCtx($parent);
    if ($ok) {
        if ($p->Err() != null) {
            $child->cancel(false, $p->Err());
        } else {
            $p->addChildren($child);
        }
        return;
    }
}

/**
 * @param Context $parent
 * @return array [cancelCtx $cancelCtx, bool]
 */
function parentCancelCtx(Context $parent) {
    $cancelCtx = null;
    $p = $parent->Value(cancelCtxKey());
    $ok = false;
    if ($p instanceof CancelCtx) {
        $ok = true;
    }
    if (!$ok) {
        return [null, false];
    }
    return [$p, true];
}


function Canceled() {
    static $Canceled = null;
    if (is_null($Canceled)) {
        $Canceled = new CancelException();
    }
    return $Canceled;
}

/**
 * @param Context $parent
 * @return CancelCtx
 */
function newCancelCtx(Context $parent) {
    return CancelCtx::newCancelCtx($parent);
}

function cancelCtxKey() {
    // 注释字符串中的md5
    // ZweiContext.CancelCtx.2021:15:21:01.admin888:mac:f0:18:98:64:32:9c
    //'ZweiCancelCtx'.md5("ZweiContext.CancelCtx.2021:15:21:01.admin888:mac:f0:18:98:64:32:9c")
    return 'ZweiCancelCtx'.'8ebd45a1bd40174fc1dbdd962f669c70';
}

/**
 * 从父 context 移除一个 CancelCtx 子节点
 * @param Context $parent
 * @param Canceler $child
 */
function removeChild(Context $parent, Canceler $child) {
    /* @var CancelCtx $p */
    list($p, $ok) = parentCancelCtx($parent);
	if (!$ok) {
        return;
	}
	if (empty($p->getChildren())) {
	    return;
    }
	foreach ($p->getChildren() as $key => $rowChild) {
        if ($rowChild === $child) {
            $p->deleteChildren($key, $child);
            break;
        }
    }
}