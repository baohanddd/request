# swoole-gearman
A job dispatcher based on swoole

Install
====

Install `swoole` first.

```
composer update baohan/swoole-job
```


How
====

Quick start

```php

$router = new \baohan\SwooleJob\Router();
$router->setPrefix("\\App\\Job\\");
$router->setExecutor("execute");
$router->setDecode(function($data) {
    return json_decode($data, true);
});

$serv = new \baohan\SwooleJob\Server($router);
$serv->setSwoolePort(9505);
// custom callback event
$serv->setEvtStart(function($serv) {
    echo "server start!" . PHP_EOL;
});
$serv->start();

```

Configure
====

Event callbacks