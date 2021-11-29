<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Barryvdh\Cors\HandleCors::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            // \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            //\App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'isLogged' => \App\Http\Middleware\isLogged::class,
        'hasLogin' => \App\Http\Middleware\hasLogin::class,
        'sacClientHasLogin' => \App\Http\Middleware\sacClientHasLogin::class,
        'sacAuthorizedHasLogin' => \App\Http\Middleware\sacAuthorizedHasLogin::class,
        'sacAuthorizedHasLogged' => \App\Http\Middleware\sacAuthorizedHasLogged::class,
        'commercialPromoterLogin' => \App\Http\Middleware\Commercial\Promoter\commercialPromoterHasLogin::class,
        'commercialPromoterHasLogged' => \App\Http\Middleware\Commercial\Promoter\commercialPromoterHasLogged::class,
		'commercialSalesmanIsLogged' => \App\Http\Middleware\Commercial\Salesman\commercialSalesmanIsLogged::class,
        'commercialSalesmanHasLogged' => \App\Http\Middleware\Commercial\Salesman\commercialSalesmanHasLogged::class,
		'commercialSalesmanIsLoggedDashboard' => \App\Http\Middleware\Commercial\Salesman\commercialSalesmanIsLoggedDashboard::class,
		'RecruitmentTestHasLogin' => \App\Http\Middleware\RecruitmentTestHasLogin::class,
        'RecruitmentTestHasLogged' => \App\Http\Middleware\RecruitmentTestHasLogged::class,
		'auth' => \App\Http\Middleware\JWTMiddleware::class,
        'Lang' => \App\Http\Middleware\Lang::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
		'cronValidation' => \App\Http\Middleware\CronValidation::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'hasPerm' => \App\Http\Middleware\hasPerm::class,
		'hasSchemePerm' => \App\Http\Middleware\Commercial\hasSchemePerm::class,
		'SecurityGuardIsLogged' => \App\Http\Middleware\EntryExit\Gate\isLogged::class,
        'SecurityGuardHasLogin' => \App\Http\Middleware\EntryExit\Gate\hasLogin::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
