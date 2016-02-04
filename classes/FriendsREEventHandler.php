<?php namespace DMA\FriendsRE\Classes;

use Session;
use DB;
use RazorsEdgeManager;
use DMA\FriendsRE\Models\RazorsEdge;
use DMA\FriendsRE\Activities\SavedMembership;

/**
 * Manage custom events in the friends platform
 *
 * @package DMA\Friends\Classes
 * @author Kristen Arnold, Carlos Arroyo
 */
class FriendsREEventHandler {

    public function subscribe($events)
    {   
        $events->listen('auth.invalidLogin', 'DMA\FriendsRE\Classes\FriendsREEventHandler@onAuthInvalidLogin');
        $events->listen('auth.register', 'DMA\FriendsRE\Classes\FriendsREEventHandler@onAuthRegister');
        $events->listen('dma.friendsre.membershipSaved', 'DMA\FriendsRE\Classes\FriendsREEventHandler@onMembershipSaved');
    }

    /**
     * If a user fails login then we need to attempt a look up on
     * Razors edge to see if an account with that member number exists
     * If it does lets create the account and send them to a registration form
     */
    public function onAuthInvalidLogin($data, $rules)
    {
        // Listening this event is not required any more
        // but I am keeping for backwards compatibility
        // Moved logic to RazorsEdgeManager
        if($re = RazorsEdgeManager::retriveByCredentials($data)){
            return true;
        }        
        return false;
    }

    /**
     * If a user is registering for the first time check if we have a
     * razorsedge stub record and merge them
     */
    public function onAuthRegister($user)
    {  
        if (!$user->razorsedge) {
            //check if a razors edge record exists and merge if it isnt connected
            $re = Razorsedge::where('email', $user->email)->first();
            RazorsEdgeManager::saveMembership($user, $re);
        }
    }

    public function onMembershipSaved($user)
    {
        SavedMembership::process($user);
    }
}
