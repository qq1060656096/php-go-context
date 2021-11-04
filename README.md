# php-go-context
> 参考 golang context https://pkg.go.dev/context

### context使用闭坑指南
```
1. 对应服务器请求应该创建一个 context 
2. 不应该在结构体 、数组、map、对象中存储context，相反应该将 context 显示的传递给每个需要它的函数
3. 它们之间的调用连必须传播上下文，使用 WithCancel() WithDeadline() WithTimeout() WithValue()
4. 当一个 context 取消时，从它派生出的所有 context 也会被取消。
5. 同一个Context可以传递给在不同的goroutine中运行的函数;上下文对于多个goroutine同时使用是安全的。
6. 不要传递 nil Context，即是函数允许。如果你不知道使用哪个 context，请使用 Todo()
7. 对于所有堵塞或者长时的操作都是应该可以取消的。
8. 使用 WithValue() 会使用流程模糊，但是有时我们不得不用，需要我们谨慎使用。
```

###  Background()
> 返回一个空context，它不会被取消，只作为根 context，用于请求级别的 顶级context

###  Todo()
> 返回一个空context，当不清楚要使用哪个 Context 或 函数还没实现 Context，你应该使用 Todo()

### WithValue()
> 
```php
include_once __DIR__.'/../vendor/autoload.php';

use function Zwei\Context\WithValue;
use function Zwei\Context\Background;


$ctx1 = WithValue(Background(), "key1", "valueV1");
$ctx2 = WithValue($ctx1, "key1", "valueV2");
var_dump($ctx1->Value("key1"));//输出：valueV1
var_dump($ctx2->Value("key1"));//输出：valueV2
```