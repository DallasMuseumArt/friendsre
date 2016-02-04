<?php namespace DMA\FriendsRE;

use Log;
use Illuminate\Support\ServiceProvider;
use DMA\FriendsRE\Classes\RazorsEdgeManager;

class FriendsREServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        // Register RecomendationManager
        $this->app['razorsedge'] = $this->app->share(function($app)
        {
            $manager = new RazorsEdgeManager;
            return $manager;
        });

        // Create alias Facade to the Notification manager
        $this->createAlias('RazorsEdgeManager', 'DMA\FriendsRE\Facades\RazorsEdge');

    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return ['razorsedge'];

    }

    /**
     * Helper method to quickly setup class aliases for a service
     *
     * @return void
     */
    protected function createAlias($alias, $class)
    {
    	$loader = \Illuminate\Foundation\AliasLoader::getInstance();
    	$loader->alias($alias, $class);
    }

}