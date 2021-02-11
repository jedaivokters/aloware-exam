<?php

namespace App\Providers;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\EloquentRepositoryInterface;

use App\Repositories\BlogRepository;
use App\Repositories\Interfaces\BlogRepositoryInterface;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
