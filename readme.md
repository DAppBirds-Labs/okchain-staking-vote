# Launch App

环境要求: php7.2+
redis

## 配置redis

- config/database.php

```

'default' => [
    'host' => env('REDIS_HOST', '127.0.0.1'),
    'password' => env('REDIS_PASSWORD', null),
    'port' => env('REDIS_PORT', 6380),
    'database' => env('REDIS_DB', 0),
],

```

## 安装 composer 依赖

> php ./composer.phar install

## 运行Application

> php -S localhost:8000 -t public

## DAppBirds 部署脚本

dappbirds production 

> php -S 172.19.16.16:8000 -t public


访问链接: http://119.28.179.45:8000