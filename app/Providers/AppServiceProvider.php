<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('valuestore', function () {
            return \Spatie\Valuestore\Valuestore::make(storage_path('app/settings.json'));
          });
       
          $values = $this->app->valuestore->all();
       
          $this->app->bind('settings', function () use($values) {
            return $values;
          });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
