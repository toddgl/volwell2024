<?php

return [
    'default-connection' => 'concrete',
    'connections' => [
        'concrete' => [
            'driver' => 'concrete_pdo_mysql',
            'server' => 'ruru',
            'database' => 'concrete5',
            'username' => 'c5admin',
            'password' => 'JQkQPgA9AUgYYiJ9',
            'character_set' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
        'jobsearch' => [
          'driver' => 'concrete_pdo_mysql',
          'server' => 'ruru',
          'database' => 'voluntee_VOLWELL2',
          'username' => 'c5admin',
          'password' => 'JQkQPgA9AUgYYiJ9',
          'charset' => 'utf8',
      ],
    ],
];
