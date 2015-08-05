<?php namespace DMA\FriendsRE\Classes;

use DMA\FriendsRE\Models\RazorsEdge;
use RainLab\User\Models\User;
use DMA\Friends\Models\Usermeta;
use Event;

/**
 * Helper class for razors edge functionality
 *
 * @package DMA\Friends\Classes
 * @author Kristen Arnold, Carlos Arroyo
 */
class RazorsEdgeManager {
    
    /**
     * Save a razors edge record to a user and handle the parsing of
     * metadata that goes along with it
     * 
     * @param User $user
     * The user object to save
     *
     * @param RazorsEdge $re
     * The razors edge stub record to connect with the user
     *
     * @return boolean
     * Returns true if the relationship was saved
     */
    public static function saveMembership(User $user, $re)
    {
        if (!$re) return;

        if (isset($re->user_id) && $re->user_id === 0) {
           
            $user->metadata->current_member_number = $re->razorsedge_id;
            
            if (strtotime($re->expires_on) >= time()) {
                $user->metadata->current_member = Usermeta::IS_MEMBER;
            } else {
                $user->metadata->current_member = Usermeta::NON_MEMBER;
            }

            $user->push();
            $user->razorsedge()->save($re);

            Event::fire('dma.friendsre.membershipSaved', [ $user ]);

            return true;
        }

        return false;
    }
}
