# PUENGASI-API REST

Diseño e Implementación de una API Rest para la comunicación con la Aplicación Móvil San Isidro que servirá para la gestión del barrio San Isidro de Puengasi - Quito

# Instalación

-   Crear BDD sanisidro utf8mb4 utf8mb4_spanish_ci

```
composer install
php artisan key:generate
php artisan make:migration create_api_public_services --path=database/migrations/App
php artisan migrate:rollback --path=database/migrations/Api
php artisan migrate:fresh --path=database/migrations/Api
php artisan migrate --path=database/migrations/Api
php artisan db:seed --class=ApiDatabaseSeeder
php artisan vendor:publish --provider="Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider" \ --tag=views
```

## Migraciones

```
composer dump-autoload
php artisan config:clear
php artisan optimize
php artisan migrate:fresh --seed #Reiniciar migraciones y correr seeders

php artisan migrate --path=database/migrations/Api/2019_08_20_200308_create_api_categories_table.php
php artisan migrate --path=database/migrations/Api/2019_08_20_182454_create_api_public_services_table.php
php artisan db:seed --class=ApiCategorySeeder
php artisan db:seed --class=ApiMobileNotificationsSeeder
```

## Pruebas

```cmd
"./vendor/bin/phpunit" ./tests/Feature/Api/
"./vendor/bin/phpunit" --filter ApiPostModuleTest
"./vendor/bin/phpunit" --filter ApiGeneralModuleTest
"./vendor/bin/phpunit" --filter ApiPublicServiceModuleTest
"./vendor/bin/phpunit" --filter ApiUserModuleTest
"./vendor/bin/phpunit" --filter ApiCategoryModuleTest
"./vendor/bin/phpunit" --filter ApiImageModuleTest
"./vendor/bin/phpunit" --filter ApiNotificationlModuleTest
```

## Remover Whoops y Añadir Ignition

composer remove filp/whoops --dev
composer require facade/ignition --dev
Si estás utilizando Laravel 5.5, 5.6 o 5.7 agrega el siguiente método a la clase Handler.php ubicada en app/Exceptions/:
protected function whoopsHandler()

```php
{
    try {
        return app(\Whoops\Handler\HandlerInterface::class);
    } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
        return parent::whoopsHandler();
    }
}
```

Si estás utilizando Laravel 5.8 asegúrate de actualizarlo a la última subversión (5.8.35 al momento de preparar esta lección).
Por último publica el archivo de configuración de ignition.php:

php artisan vendor:publish --provider="Facade\Ignition\IgnitionServiceProvider" --tag="ignition-config"
y por medidas de seguridad desactiva la opción de compartir:
PHP

```php
<?php

// config/ignition.php
return [
    //...
    'enable_share_button' => false,
    //...
];
```

# Ejemplo Consultas

```sql
-- Listar Likes
select * from reactions where post_id = 1;
--Listar Post por Subcategoria
select * from posts where subcategory_id = 2;

```

## Autor

> Stalin Maza - Desarrollador Web - Móvil
