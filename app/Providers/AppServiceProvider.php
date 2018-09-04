<?php

namespace App\Providers;

use App\Repositories\ArticleRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\UserRepository;
use App\Services\API\ArticleService;
use App\Services\API\AuthorService;
use App\Services\API\CategoryService;
use App\Services\ClientAPI\ClientArticleService;
use App\Services\ClientAPI\ClientAuthorService;
use App\Services\ClientAPI\ClientCategoryService;
use App\Services\FrontCategoryService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
     * @throws \Exception
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if (!Auth::user() && Schema::hasTable('categories')) {

            View::share(
                'categories',
                $this->app->make(FrontCategoryService::class)->getList()
            );
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerRepositories();
        $this->registerServices();
    }

    /**
     *
     */
    private function registerServices(): void
    {
        $this->app->singleton(ArticleService::class);
        $this->app->singleton(AuthorService::class);
        $this->app->singleton(CategoryService::class);
        $this->app->singleton(UserService::class);

        $this->app->singleton(ClientAuthorService::class);
        $this->app->singleton(ClientCategoryService::class);
        $this->app->singleton(ClientArticleService::class);

        $this->app->singleton(FrontCategoryService::class);
    }

    /**
     *
     */
    private function registerRepositories(): void
    {
        $this->app->singleton(AuthorRepository::class);
        $this->app->singleton(CategoryRepository::class);
        $this->app->singleton(ArticleRepository::class);
        $this->app->singleton(UserRepository::class);
    }
}
