# php-go-context
> 参考 golang context https://pkg.go.dev/context
> 

###  Background()
> 返回一个空context，它不会被取消，只作为根 context，用于请求级别的 顶级context

###  Todo()
> 返回一个空context，当不清楚要使用哪个 Context 或 函数还没实现 Context，你应该使用 Todo()

### WithValue 使用
```php
include_once __DIR__.'/../vendor/autoload.php';

use function Zwei\Context\WithValue;
use function Zwei\Context\Background;


$ctx1 = WithValue(Background(), "key1", "valueV1");
$ctx2 = WithValue($ctx1, "key1", "valueV2");
var_dump($ctx1->Value("key1"));//输出：valueV1
var_dump($ctx2->Value("key1"));//输出：valueV2
```