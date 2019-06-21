# GenerateSchema

[![Build Status](https://drone.snowcookie.moe/api/badges/snowshana/GenerateSchema/status.svg?ref=refs/heads/2.x)](https://drone.snowcookie.moe/snowshana/GenerateSchema)

> Gernerate schema from database

## Requirement
- Laravel 5.8

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
php artisan vendor:publish --tag=generate-schema-command
```

- generate scheam with command
```
php artisan tools:generate_schema_command --storage_disk=local
```

## Support Database

- Mysql 8.0
- Postgres 11.3

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

- Plain text txt files

> example: migrations.txt

```
+-----------+------------------+-----+----------+---------+-----------------+------------+
| name      | type             | key | nullable | default | constraint_name | referenced |
+-----------+------------------+-----+----------+---------+-----------------+------------+
| id        | int(10) unsigned | PRI | NO       |         |                 |            |
| migration | varchar(255)     |     | NO       |         |                 |            |
| batch     | int(11)          |     | NO       |         |                 |            |
+-----------+------------------+-----+----------+---------+-----------------+------------+
```

## Extend Renderer

```
<?php

use Snowcookie\GenerateSchema\Contracts\GeneratorRenderer;

class CustomGeneratorRenderer implements GeneratorRenderer
{
    ...
}

```
