<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default
    |--------------------------------------------------------------------------
    |
    | Resolve databasemanager base on default database config.
    | Output scheam to plain txt files.
    |
    */
   'use_default' => true,

    /*
    |--------------------------------------------------------------------------
    | Database Manager
    |--------------------------------------------------------------------------
    |
    | Specify database manager binding to generator,
    | require `use_default` set false.
    |
    */

    'database_manager' => Snowcookie\GenerateSchema\DatabaseManagers\MysqlManager::class,

    /*
    |--------------------------------------------------------------------------
    | Renderer
    |--------------------------------------------------------------------------
    |
    | Specify renderer binding to generator,
    | require `use_default` set false.
    |
    */

    'renderer' => Snowcookie\GenerateSchema\Renderers\TxtRenderer::class,
];
