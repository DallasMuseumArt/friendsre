<?php namespace DMA\FriendsRE\Components;

use RainLab\User\Models\User;
use DMA\Friends\Models\Usermeta;
use DMA\Friends\Classes\UserExtend;
use Cms\Classes\ComponentBase;
use Str;
use Flash;
use Redirect;
use Auth;

class VerifyMembership extends ComponentBase
{

    use \System\Traits\ViewMaker;

    /**
     * {@inheritDoc}
     */
    public function componentDetails()
    {
        return [
            'name'          => 'Verify Membership',
            'description'   => 'Provide a form to verify that a member exists',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function onRun()
    {
        $re = session('re');

        if (!$re) {
            return Redirect::intended('/');
        }

        $this->page['member_id'] = $re->razorsedge_id;

        $this->addCss('components/verifymembership/assets/verify-membership.css');
    }

    public function onSubmit()
    {
        $first_name = post('first_name');
        $last_name = post('last_name');
        $re = session('re');

        if (Str::lower($re->first_name) == Str::lower($first_name)
            && Str::lower($re->last_name) == Str::lower($last_name)) {

            $this->page['re'] = $re;
            $this->page['options'] = Usermeta::getOptions();

            return [
                '#layout-content' => $this->renderPartial('@register'),
            ];

        } else {
            Flash::error('The first and last name did not match our records');
        }

        return [
            '#flashMessages' => $this->renderPartial('@flashMessages'),
        ];

    }

    public function onRegister()
    {
        $vars = post();
        $user = new User($vars);
        $user->metadata = new Usermeta;
        $user->metadata->first_name = $vars['metadata']['first_name'];
        $user->metadata->last_name = $vars['metadata']['last_name'];
        $user->is_activated = 1;
        
        $re = session('re');

        if ($user->push() && $user->razorsedge()->save($re)) {
            Auth::login($user);
            return Redirect::intended('friends');
        }

        Flash::error(Lang::get('dma.friends::lang.user.saveFailed'));

        return [
            '#flashMessages' => $this->renderPartial('@flashMessages'),
        ];
    }
}