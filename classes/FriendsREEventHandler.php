<?php namespace DMA\FriendsRE\Classes;

use Session;
use DB;
use DMA\FriendsRE\Models\Razorsedge;
use DMA\FriendsRE\Classes\RazorsEdgeManager;
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

        $re = Razorsedge::where('razorsedge_id', $data['login'])->first();

        if ($re && !$re->user_id) {
            Session::put(['authRedirect' => 'verify-membership']);
            Session::put(['re' => $re]);
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
        //check if a razors edge record exists and merge if it isnt connected
        $re = Razorsedge::where('email', $user->email)->first();
        RazorsEdgeManager::saveMembership($user, $re);
    }

    public function onMembershipSaved($user)
    {
        SavedMembership::process($user);
    }
}