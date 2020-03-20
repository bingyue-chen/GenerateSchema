# GenerateSchema

[![Build Status](https://drone.snowcookie.moe/api/badges/snowshana/GenerateSchema/status.svg?ref=refs/heads/4.x)](https://drone.snowcookie.moe/snowshana/GenerateSchema)

> Generate schema from database

## Requirement

 Laravel  | GenerateSchema
:---------|:----------------
 6.x      | 3.x
 7.x      | 4.x

## Usage

- add packeage
```
composer require --dev snowcookie/generate-schema
```

- add conditionally loading service provider to app/Providers/AppServiceProvider.php
```
public function register()
{
    ...

    if ($this->app->environment('local')) {
        $this->app->register('Snowcookie\GenerateSchema\GenerateSchemaServiceProvider');
    }
    ...
}
```

- publish config file
```
php artisan vendor:publish --tag=generate-schema
```

- generate scheam with command
```
php artisan tools:generate_schema_command --storage_disk=local
```

## Support Database

- Mysql 8     (MysqlManager)
- Postgres 11 (PostgresManager)

## Extend Database Manager

```
<?php

use Snowcookie\GenerateSchema\Contracts\GeneratorDatabaseManager;

class CustomGeneratorDatabaseManager implements GeneratorDatabaseManager
{
    ...
}

```

## Support Renderer

- Plain text (TxtRenderer, per table one file)

> example: migrations.txt

```
+-----------+------------------+-----+----------+---------+-----------------+------------+------------+
| name      | type             | key | nullable | default | constraint_name | index_name | referenced |
+-----------+------------------+-----+----------+---------+-----------------+------------+------------+
| id        | int(10) unsigned | PRI | NO       |         |                 | PRIMARY    |            |
| migration | varchar(255)     |     | NO       |         |                 |            |            |
| batch     | int(11)          |     | NO       |         |                 |            |            |
+-----------+------------------+-----+----------+---------+-----------------+------------+------------+
```

- Csv  (CsvRenderer, per table one file, delimiter `,`)
- Xlsx (XlsxRenderer, one file, per table one sheet)

## Extend Renderer

```
<?php

use Snowcookie\GenerateSchema\Contracts\GeneratorRenderer;

class CustomGeneratorRenderer implements GeneratorRenderer
{
    ...
}

```
