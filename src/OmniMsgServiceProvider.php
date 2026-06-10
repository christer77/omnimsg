<?php

namespace OmniMsg;

use Illuminate\Support\ServiceProvider;
use OmniMsg\Channels\ChannelManager;

class OmniMsgServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge the package default config with the application config
        $this->mergeConfigFrom(__DIR__.'/../config/omnimsg.php', 'omnimsg');

        // Register the ChannelManager as a singleton
        $this->app->singleton(ChannelManager::class, function ($app) {
            return new ChannelManager($app);
        });

        // Register an alias for the facade
        $this->app->alias(ChannelManager::class, 'omnimsg');
    }

    public function boot()
    {
        // Publish the config to the project
        $this->publishes([
            __DIR__.'/../config/omnimsg.php' => config_path('omnimsg.php'),
        ], 'omnimsg-config');
    }
}
