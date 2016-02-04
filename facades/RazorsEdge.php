<?php namespace DMA\FriendsRE\Facades;

use Illuminate\Support\Facades\Facade;

class RazorsEdge extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * Resolves to:
     * - DMA\Recommendations\Classes\RecommendationManager
     *
     * @return string
     */
    protected static function getFacadeAccessor(){ 
        return 'razorsedge';
    }
}