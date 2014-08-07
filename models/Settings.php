<?php namespace DMA\FriendsRE\Models;

use Model;

/**
 * Settings Model
 */
class Settings extends Model
{

    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'dma_friendsre_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
