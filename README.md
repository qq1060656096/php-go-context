# php-go-context
> 参考 golang context https://pkg.go.dev/context

### context使用闭坑指南
```
1. 将一个Context参数作为第一个参数传递给传入和传出请求之间调用路径上的每个函数
2. 对应服务器请求应该创建一个 context 
3. 不应该在结构体 、数组、map、对象中存储context，相反应该将 context 显示的传递给每个需要它的函数
4. 它们之间的调用连必须传播上下文，使用 WithCancel() WithDeadline() WithTimeout() WithValue()
5. 当一个 context 取消时，从它派生出的所有 context 也会被取消。
6. 同一个Context可以传递给在不同的goroutine中运行的函数;上下文对于多个goroutine同时使用是安全的。
7. 不要传递 nil Context，即是函数允许。如果你不知道使用哪个 context，请使用 Todo()
8. 对于所有堵塞或者长时的操作都是应该可以取消的。
9. 使用 WithValue() 会使用流程模糊，但是有时我们不得不用，需要我们谨慎使用。
10. 开发业务时，老代码函数不必遵守，Context参数作为老函数的第一个参数。
11. 开发业务时，如果有新旧代码，请保持新代码必须传 context
12. 开发业务时，如果有新旧代码，请保持老代码没有传递 context 做兼容，如设置默认值
```

###  Background()
> 返回一个空context，它不会被取消，只作为根 context，用于请求级别的 顶级context

###  Todo()
> 返回一个空context，当不清楚要使用哪个 Context 或 函数还没实现 Context，你应该使用 Todo()

### WithValue()
> 返回 携带 parent 副本的ValueCtx并把key val关联，用于请求范围内的数据使用上下文值。
> 使用 WithValue() 会使用流程模糊，但是有时我们不得不用，需要我们谨慎使用。
```php
include_once __DIR__.'/../vendor/autoload.php';

use function Zwei\Context\WithValue;
use function Zwei\Context\Background;


$ctx1 = WithValue(Background(), "key1", "valueV1");
$ctx2 = WithValue($ctx1, "key1", "valueV2");
var_dump($ctx1->Value("key1"));//输出：valueV1
var_dump($ctx2->Value("key1"));//输出：valueV2
```

### WithCancel()
> 返回带有父级副本 CancelCtx，当返回的 cancel函数被调用时，当前 context 和 子context Done 返回 true。
> CancelCtx释放与此上下有关的资源，因此代码应该在上下文中运行操作完成或者异常后调用。
```php
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

```

### WithDeadline()还未写完
> 