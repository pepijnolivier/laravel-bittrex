<?php
namespace Pepijnolivier\Bittrex;

use Illuminate\Support\ServiceProvider;

class PoloniexServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/poloniex.php' => config_path('bittrex.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bittrex', function () {
            return new BittrexManager;
        });
    }
}
