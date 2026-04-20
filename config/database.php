<?php
declare (strict_types=1);

return [
    'driver' => 'mysql',
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'database' => getenv('DB_DATABASE') ?: 'demo',
    'username' => getenv('DB_USERNAME') ?: 'demo',
    'password' => getenv('DB_PASSWORD') ?: 'TbfXmL2JTcXYYrWZ',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => getenv('DB_PREFIX') ?: 'acg_',
];
