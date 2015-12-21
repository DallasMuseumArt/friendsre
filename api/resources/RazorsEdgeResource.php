<?php namespace DMA\FriendsRE\API\Resources;

use Response;
use DMA\Friends\Classes\API\BaseResource;
use DMA\FriendsRE\Models\RazorsEdge;

class RazorsEdgeResource extends BaseResource {

    protected $model        = '\DMA\FriendsRE\Models\RazorsEdge';

    protected $transformer  = '\DMA\FriendsRE\API\Transformers\RazorsEdgeTransformer';

    
    public function __construct()
    {
        // Add additional routes to Activity resource
        $this->addAdditionalRoute('membershipByUserID', 'user/{id}',            ['GET']);
    }
    
    
    /**
     * @SWG\Get(
     *     path="razors-edge",
     *     description="Returns all Razor Edge data",
     *     summary="Return all Razors Edge that match a Friends user", 
     *     tags={ "razors edge"},
     *
     *     @SWG\Parameter(
     *         ref="#/parameters/authorization"
     *     ),
     *     @SWG\Parameter(
     *         ref="#/parameters/per_page"
     *     ),
     *     @SWG\Parameter(
     *         ref="#/parameters/page"
     *     ),
     *     @SWG\Parameter(
     *         ref="#/parameters/sort"
     *     ),
     *    
     *     @SWG\Response(
     *         response=200,
     *         description="Successful response",
     *         @SWG\Schema(ref="#/definitions/razor_edge.membership", type="array")
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @SWG\Schema(ref="#/definitions/error500")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found",
     *         @SWG\Schema(ref="#/definitions/error404")
     *    )
     * )
     */
    public function index()
    {
        return parent::index();
    }
    
    /**
     * @SWG\Get(
     *     path="razors-edge/{id}",
     *     description="Returns a Razors Edge membership by id",
     *     summary="Find a Razors Edge membership  by sync ID",
     *     tags={ "razors edge"},
     *
     *     @SWG\Parameter(
     *         ref="#/parameters/authorization"
     *     ),
     *     @SWG\Parameter(
     *         description="ID of Razor Edge Sync ID to fetch",
     *         format="int64",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *
     *     @SWG\Response(
     *         response=200,
     *         description="Successful response",
     *         @SWG\Schema(ref="#/definitions/razor_edge.membership")
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @SWG\Schema(ref="#/definitions/error500")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found",
     *         @SWG\Schema(ref="#/definitions/error404")
     *     )
     * )
     */
    public function show($id)
    {
        return parent::show($id);
    }
    
    
    
    /**
     * @SWG\Get(
     *     path="razors-edge/user/{id}",
     *     description="Returns a Razors Edge membership by friends user id",
     *     summary="Find a Razors Edge membership  by friends user ID",
     *     tags={ "razors edge"},
     *
     *     @SWG\Parameter(
     *         ref="#/parameters/authorization"
     *     ),
     *     @SWG\Parameter(
     *         description="ID of friends user  to fetch",
     *         format="int64",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *
     *     @SWG\Response(
     *         response=200,
     *         description="Successful response",
     *         @SWG\Schema(ref="#/definitions/razor_edge.membership")
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @SWG\Schema(ref="#/definitions/error500")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found",
     *         @SWG\Schema(ref="#/definitions/error404")
     *     )
     * )
     */
    public function membershipByUserID($userFriendId)
    {
        if( $re = RazorsEdge::where('user_id', $userFriendId)->first()){
            $data = Response::api()->withItem($re, new $this->transformer);
            return $data; 
        }else{
           return Response::api()->errorNotFound('User not found');
        }
        
        
    }
    
}
