<?php namespace DMA\FriendsRE;

use System\Classes\PluginBase;
use Illuminate\Support\Facades\Event;
use Rainlab\User\Models\User as User;

/**
 * FriendsRE Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'FriendsRE',
            'description' => 'Manage Razors Edge data',
            'author'      => 'Dallas Museum of Art',
            'icon'        => 'icon-cutlery'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function boot()
    {   
        // Extend the user model to support our custom metadata
        User::extend(function($model) {
            $model->hasOne['razorsedge'] = ['DMA\FriendsRE\Models\RazorsEdge'];
        }); 

        Event::listen('backend.form.extendFields', function($widget) {
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) return;
            if ($widget->getContext() != 'update') return;

            $widget->addFields([
                'razorsedge[razorsedge_id]' => [
                    'label' => 'Constituent ID',
                    'tab'   => 'Razors Edge',
                ], 
                'razorsedge[expires_on]' => [
                    'label' => 'Expires On',
                    'tab'   => 'Razors Edge',
                ],
                'razorsedge[first_name]' => [
                    'label' => 'First Name',
                    'tab'   => 'Razors Edge',
                ],
                'razorsedge[last_name]' => [
                    'label' => 'Last Name',
                    'tab'   => 'Razors Edge',
                ],
               'razorsedge[full_name]' => [
                    'label' => 'Full Name',
                    'tab'   => 'Razors Edge',
                ],
               'razorsedge[address]' => [
                    'label' => 'Address',
                    'tab'   => 'Razors Edge',
                ],
               'razorsedge[city]' => [
                    'label' => 'City',
                    'tab'   => 'Razors Edge',
                ],
               'razorsedge[state]' => [
                    'label' => 'State',
                    'tab'   => 'Razors Edge',
                ],
               'razorsedge[zip]' => [
                    'label' => 'Zip',
                    'tab'   => 'Razors Edge',
                ],
            ], 'primary');
        });

        Event::listen('backend.list.extendColumns', function($widget) {
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) return;

            $widget->addColumns([
                'razorsedge_id' => [
                    'label' => 'Constituent ID',
                    'relation' => 'razorsedge',
                    'select' => '@razorsedge_id',
                ],  
                'address' => [
                    'label' => 'RE - Address',
                    'relation' => 'razorsedge',
                    'select' => '@address',
                ], 
                'state' => [
                    'label' => 'RE - State',
                    'relation' => 'razorsedge',
                    'select' => '@state',
                ], 
                'city' => [
                    'label' => 'RE - City',
                    'relation' => 'razorsedge',
                    'select' => '@city',
                ], 
                'zip' => [
                    'label' => 'RE - Zip',
                    'relation' => 'razorsedge',
                    'select' => '@zip',
                ], 
                'expires_on' => [
                    'label' => 'RE - Membership Expires',
                    'relation' => 'razorsedge',
                    'select' => '@expires_on',
                ], 
                
            ]);
        });
    }   

    /**
     * {@inheritDoc}
     */
    public function register()
    {   
        $this->registerConsoleCommand('friends.sync-razorsedge-data', 'DMA\FriendsRE\Commands\SyncRazorsEdgeDataCommand');
    }  

    /**
     * {@inheritDoc}
     */
    public function registerSchedule($schedule)
    {
        $schedule->command('friends.sync-razorsedge-data')->daily();
    }

    /**
     * {@inheritDoc}
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Razors Edge Settings',
                'description' => 'Manage razors edge based settings.',
                'category'    => 'Friends',
                'icon'        => 'icon-cog',
                'class'       => 'DMA\FriendsRE\Models\Settings',
                'order'       => 500,
                'keywords'    => 'friends razorsedge'
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function registerReportWidgets()
    {
        return [
            'DMA\FriendsRE\ReportWidgets\FriendsMembers'=>[
                'label'   => 'Chart - Friends/Partners Ratio',
                'context' => 'dashboard'
            ],
        ];
    }

}
