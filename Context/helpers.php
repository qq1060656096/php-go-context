<?php
namespace Zwei\Context;

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


//
///**
// * @return array [Context $ctx,CancelFunc $cancel]
// */
//function WithCancel()
//{
//    $c = newCancelCtx($parent);
//    propagateCancel(parent, $c);
//    return [$c, function() use($c){
//        $c->cancel(false, Canceled());
//    }];
//}
//
//
//function propagateCancel(Context $parent, canceler $child) {
//    // 1. 父 context 取消，就取消子 context
//    $done = $parent->Done();
//    if ($done) {
//        $child->cancel(false, $parent->Err());
//        return;
//    }
//    /* @var CancelCtx $p*/
//    /* @var bool $ok */
//    list($p, $ok) = parentCancelCtx(parent);
//    if ($ok) {
//        if ($p->Err() != nil) {
//            $child->cancel(false, $p->Err());
//        } else {
//            if ($p->getChildren() == nil) {
//                $p->setChildren([]);
//            }
//            $children = $p->getChildren();
//            $children[$child] = $child;
//            $p->setChildren($children);
//        }
//        return;
//    }
//}
//
///**
// * @param Context $parent
// * @return array [cancelCtx $cancelCtx, bool]
// */
//function parentCancelCtx(Context $parent) {
//    $done = $parent->Done();
//    $cancelCtx = null;
//    if ($done) {
//        return [$cancelCtx, true];
//    }
//    $p = parent.Value(cancelCtxKey());
//    $ok = false;
//    if ($p instanceof CancelCtx) {
//        $ok = true;
//        $cancelCtx = $p;
//    }
//    if (!$ok) {
//        return [$cancelCtx, false];
//    }
//
//    return [$cancelCtx, true];
//}
//
//function cancelCtxKey() {
//    static $cancelCtxKey = 0;
//    return sprintf('%', $cancelCtxKey);
//}
//
//function Canceled() {
//    static $Canceled = null;
//    if (is_null($Canceled)) {
//        $Canceled = new \Exception();
//    }
//    return $Canceled;
//}