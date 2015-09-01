<?php namespace DMA\FriendsRE\API\Transformers;

use Response;

use DMA\Friends\Classes\API\BaseTransformer;
use DMA\Friends\API\Transformers\DateTimeTransformerTrait;

class RazorsEdgeTransformer extends BaseTransformer {
    
    use DateTimeTransformerTrait;
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
            
    ];
    
    /**
     * @SWG\Definition(
     *    definition="razor_edge.membership",
     *    @SWG\Property(
     *         property="id",
     *         type="integer",
     *         format="int32"
     *    ),
     *    @SWG\Property(
     *         property="user_id",
     *         type="integer",
     *         format="int32"
     *    ),
     *    @SWG\Property(
     *         property="razor_edge_id",
     *         type="string",
     *    ),
     *    @SWG\Property(
     *         property="member_id",
     *         type="integer",
     *         format="int32"
     *    ),
     *    @SWG\Property(
     *         property="expires_on",
     *         type="string",
     *    ),
     *    @SWG\Property(
     *         property="first_name",
     *         type="string",
     *    ),
     *    @SWG\Property(
     *         property="last_name",
     *         type="string",
     *    ),
     *    @SWG\Property(
     *         property="address",
     *         type="string",
     *    ), 
      *    @SWG\Property(
     *         property="city",
     *         type="string",
     *    ),
     *    @SWG\Property(
     *         property="state",
     *         type="string",
     *    ),
     *    @SWG\Property(
     *         property="zip",
     *         type="integer",
     *         format="int32"
     *    ),
     *    @SWG\Property(
     *         property="member_level",
     *         type="integer",
     *         format="int32"
     *    ),
     *    @SWG\Property(
     *         property="email",
     *         type="string",
     *    )     
     * )
     */
    

    /**
     * {@inheritDoc}
     * @see \DMA\Friends\Classes\API\BaseTransformer::getData()
     */
    
    public function getData($instance)
    {
        $data = [
                'id'                    => (int)$instance->id, 
                'user_id'               => (int)$instance->user_id, 
                'razors_edge_id'        => $instance->razorsedge_id,
                'member_id'             => (int)$instance->member_id,
                'expires_on'            => $instance->expires_on,
                'first_name'            => $instance->first_name,
                'last_name'             => $instance->last_name,
                'address'               => $instance->address,
                'city'                  => $instance->city,
                'state'                 => $instance->state,                
                'zip'                   => $instance->zip,
                'member_level'          => (int)$instance->member_level,
                'email'                 => $instance->email,
                
                
        ];
        
        return $data;
    }
    

}
