<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=mariadb;port=3306;dbname=monitor',
    'username' => 'root',
    'password' => 'i9rHtreECvyuZQ',
    'charset' => 'utf8',
    'on afterOpen' => function ($event) {
        $event->sender->createCommand("SET time_zone='Asia/Jakarta';")->execute();
    },

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',

];
