<?php
namespace DMA\FriendsRE\Activities;

use RainLab\User\Models\User;
use DMA\Friends\Models\Activity;
use DMA\Friends\Classes\ActivityTypeBase;

class SavedMembership extends ActivityTypeBase
{

    /**
     * {@inheritDoc}
     */
    public function details()
    {
        return [
            'name'          => 'Saved Membership',
            'description'   => 'award activities when a user becomes a member and the accounts are linked',
        ];
    }

    /**
     * @see \DMA\Friends\Classes\ActivityTypeBase
     *
     * Process and determine if an award can be isued
     * based on a provided activity code
     *
     * @param User $user
     * A user model for which the activity should act upon
     * 
     * @param array $params
     * An array of parameters for validating activities 
     *
     * @return boolean
     * returns true if the process was successful
     */
    public static function process(User $user, $params = [])
    {

        $activities = Activity::findActivityType('SavedMembership')->get();

        foreach ($activities as $activity) {
            parent::process($user, ['activity' => $activity]);
        }

        return true;
    }
}
