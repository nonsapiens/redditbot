<?php

namespace Redditbot\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class RedditbotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
		$this->publishes([
			                 realpath( dirname( __FILE__ ) . '/../config/redditbot.php' )        => config_path( 'redditbot.php' ),
		                 ], 'redditbot');

	    $this->mergeConfigFrom( realpath( dirname( __FILE__ ) . '/../config/redditbot.php' ), 'redditbot' );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
