<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| This script creates the Laravel application instance which serves as
| the "glue" for all the components, and is the IoC container for the
| system binding all of the various parts together.
|
*/

use Illuminate\Foundation\Application;

/*
|--------------------------------------------------------------------------
| Create The Application Instance
|--------------------------------------------------------------------------
*/
$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Here we bind the Console Kernel, HTTP Kernel, and Exception Handler
| into the container. This is Laravel 10 default.
|
*/

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
*/

return $app;
