<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->removeIndexFromUrl();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace('App\\Http\\Controllers\\Api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace('App\\Http\\Controllers\\Web')
                ->group(base_path('routes/web.php'));
        });
    }

     /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            $by = $request->ip();

            try {
                $by = optional($request->user('api'))->id;
            } catch (\Exception $e) { }

            return Limit::perMinute(config('app.api_rate_limit', 60))->by($by);
        });
    }

    /**
     * Remove /index.php/ from urls to prevent content duplication and broken links
     *
     * @see https://thewebtier.com/remove-index-php-from-the-url-in-laravel
     */
    protected function removeIndexFromUrl()
    {
        if (Str::contains(request()->getRequestUri(), '/index.php/')) {
            $url = str_replace('index.php/', '', request()->getRequestUri());

            if (strlen($url) > 0) {
                header("Location: $url", true, 302);
                exit;
            }
        }
    }
}
