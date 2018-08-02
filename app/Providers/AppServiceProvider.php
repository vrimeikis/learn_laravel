<?php

namespace App\Providers;

use App\Services\API\ArticleService;
use App\Services\API\AuthorService;
use App\Services\ClientAPI\ClientArticleService;
use App\Services\ClientAPI\ClientAuthorService;
use App\Services\ClientAPI\ClientCategoryService;
use App\Services\UserService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerServices();
    }

    /**
     *
     */
    private function registerServices(): void
    {
        $this->app->singleton(ArticleService::class);
        $this->app->singleton(AuthorService::class);
        $this->app->singleton(UserService::class);

        $this->app->singleton(ClientAuthorService::class);
        $this->app->singleton(ClientCategoryService::class);
        $this->app->singleton(ClientArticleService::class);
    }
}
