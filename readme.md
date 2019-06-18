# GenerateSchema

[![Build Status](https://drone.snowcookie.moe/api/badges/snowshana/GenerateSchema/status.svg?ref=refs/heads/master)](https://drone.snowcookie.moe/snowshana/GenerateSchema)

> Gernerate scheam form database

## Requirement
- Laravel 5.4

## Usage

- add service provider to config/app.php
``` 
	'providers' => [
	...

		Snowcookie\GenerateSchema\GenerateSchemaServiceProvider::class,

	...
	],
```

- publish config file
```
php artisan vendor:publish --tag=generate-schema-command
```

## Support Database
- Mysql 8.0

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

## Extend Renderer

```
<?php

use Snowcookie\GenerateSchema\Contracts\GeneratorRenderer;

class CustomGeneratorRenderer implements GeneratorRenderer
{
	...
}

```
