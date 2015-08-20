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
    public $belongsTo = [
        'user' => ['RainLab\User\Models\User',
            'key' => 'user_id',        
        ],
    ];


}
