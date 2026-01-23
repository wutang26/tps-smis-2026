<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'check_active_session' => \App\Http\Middleware\CheckActiveSessionProgramme::class,
            'student' => \App\Http\Middleware\RestrictToStudents::class,
            'check.student.status' => \App\Http\Middleware\CheckStudentStatus::class,
            'checkCourseInstructor' => \App\Http\Middleware\CheckCourseInstructor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();