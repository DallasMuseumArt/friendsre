<?php namespace DMA\FriendsRE\Classes;

use DMA\FriendsRE\Models\RazorsEdge;
use RainLab\User\Models\User;
use DMA\Friends\Models\Usermeta;

/**
 * Helper class for razors edge functionality
 *
 * @package DMA\Friends\Classes
 * @author Kristen Arnold, Carlos Arroyo
 */
class RazorsEdgeManager {
    
    public static function saveMembership(User $user, RazorsEdge $re)
    {

        if (isset($re->user_id) && $re->user_id === 0) {
           
            $user->metadata->current_member_number = $re->razorsedge_id;
            
            if (strtotime($re->expires_on) >= time()) {
                $user->metadata->current_member = Usermeta::IS_MEMBER;
            } else {
                $user->metadata->current_member = Usermeta::NON_MEMBER;
            }

            $user->push();
            $user->razorsedge()->save($re);

            return true;
        }

        return false;
    }
}