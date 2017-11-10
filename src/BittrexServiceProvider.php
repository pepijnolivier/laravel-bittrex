<?php
namespace Pepijnolivier\Bittrex;

use Illuminate\Support\ServiceProvider;

class BittrexServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/bittrex.php' => config_path('bittrex.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bittrex.php', 'bittrex');

        $this->app->singleton('bittrex', function () {
            return new BittrexManager;
        });
    }
}
