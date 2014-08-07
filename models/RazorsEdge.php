<?php namespace DMA\FriendsRE\Models;

use Model;

/**
 * RazorsEdge Model
 */
class RazorsEdge extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'dma_friendsre_razors_edges';
    public $timestamps = false;

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}
