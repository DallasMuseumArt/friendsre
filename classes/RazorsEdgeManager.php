<?php namespace DMA\FriendsRE\Classes;

use Event;
use Session;
use RainLab\User\Models\User;
use DMA\Friends\Models\Usermeta;
use DMA\Friends\Classes\FriendsMembershipInterface;
use DMA\FriendsRE\Models\RazorsEdge;


/**
 * Helper class for razors edge functionality
 *
 * @package DMA\Friends\Classes
 * @author Kristen Arnold, Carlos Arroyo
 */
class RazorsEdgeManager implements FriendsMembershipInterface {
    
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
    public function saveMembership(User $user, $re)
    {

        if (!$re) return;

        if (isset($re->user_id) && (int)$re->user_id === 0) {
            
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
    
    
    public function retriveById($id) 
    {
        return Razorsedge::where('id', $id)->first();
    }
    
    public function retriveByCredentials(array $credentials) 
    {
        $re = Razorsedge::where('razorsedge_id', $credentials['login'])->first();
        
        if ($re && !$re->user_id) {
            // Keep for backwards compatibility
            // Both session variables are used by UserLogin 
            // and VerifyMembership components
            Session::put(['authRedirect' => 'verify-membership']);
            Session::put(['re' => $re]);
                        
            return $re;
        }
        
        return null;
    }
    
    public function verifyMembership($membershipData, array $inputData)
    {   
        $verify = false;
        
        // Extract Razors Edge data to validate user from token
        $reMemberID   = array_get($membershipData,   'razormember_id', null);
        $reFirstName  = array_get($membershipData,   'first_name', null);
        $reLastName   = array_get($membershipData,   'last_name', null);
        $reEmail      = array_get($membershipData,   'email', null);
    
    
        // Validated membership
        $firstName  = array_get($inputData, 'first_name', null);
        $lastName   = array_get($inputData, 'last_name', null);
        $email      = array_get($inputData, 'email', null);
    
        // 2. Verify membership matching first and last names
        if ($firstName !== null and $lastName !== null){
            $verify = ( $this->compareString($firstName, $reFirstName)) &&
            ($this->compareString($lastName, $reLastName));
             
        }
    
        // 3. Verify membership matching Email address
        if (!$verify && $email !== null) {
            $verify = ($this->compareString($email, $reEmail));
        }

        return $verify;
    
    }
    
    public function getMembershipHintsAttributes() 
    {
       return ['email', 'first_name', 'last_name'];
    }
    
    /**
     * Helper funtion to compare strings
     * after normalize them
     * @private
     * @param unknown $str
     */
    private function  compareString($str1, $str2){
        $re = "/ {2,}/";
        // Normalize spaces
        $str1 = preg_replace($re, ' ', $str1);
        $str2 = preg_replace($re, ' ', $str2);
                
        return trim(strtolower($str1)) == trim(strtolower($str2));
    }
    
    
}
