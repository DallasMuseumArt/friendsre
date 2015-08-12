<?php namespace DMA\FriendsRE\Components;

use RainLab\User\Models\User;
use DMA\Friends\Models\Usermeta;
use DMA\Friends\Classes\UserExtend;
use DMA\Friends\Classes\AuthManager;
use DMA\FriendsRE\Models\RazorsEdge;
use DMA\FriendsRE\Classes\RazorsEdgeManager;
use Cms\Classes\ComponentBase;
use Str;
use Flash;
use Lang;
use Redirect;
use Auth;
use Session;

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
        $re = Session::get('re');
        $isIOS = get('isIOS');

        Session::put('isIOS', $isIOS);

        if (!$re) {
            $this->page['member_id'] = false;
        } else {
            $this->page['member_id'] = $re->razorsedge_id;
        }

        $this->addCss('components/verifymembership/assets/verify-membership.css');
    }

    public function onSubmit()
    {
        $first_name = post('first_name');
        $last_name  = post('last_name');
        $re         = Session::pull('re');

        if (!$re && $member_id = post('member_id')) {
            $re = RazorsEdge::where('razorsedge_id', $member_id)->first();
        }

        if ($re 
            && Str::lower($re->first_name) == Str::lower($first_name)
            && Str::lower($re->last_name) == Str::lower($last_name)) {

            Session::put('re', $re); // resave the session if we are going to continue
            $this->page['re']       = $re;
            $this->page['options']  = Usermeta::getOptions();

            return [
                '#layout-content' => $this->renderPartial('@register'),
            ];

        } else {
            Flash::error('The information did not match our records');
        }

        return [
            '#flashMessages' => $this->renderPartial('@flashMessages'),
        ];

    }

    public function onRegister()
    {
        $vars   = post();
        $user   = AuthManager::register($vars);
        $re     = Session::pull('re');
        $isIOS  = Session::pull('isIOS');

        if (RazorsEdgeManager::saveMembership($user, $re)) {
            Auth::login($user);

            if ($isIOS) {
                return [
                    '#layout-content' => $this->renderPartial('@iosComplete', [
                        'email'     => $user->email,
                        'password'  => $vars['password'],
                    ])
                ];
            }
      
            return Redirect::intended('/');

        } else {

            Flash::error(Lang::get('dma.friends::lang.user.saveFailed'));

            return [
                '#flashMessages' => $this->renderPartial('@flashMessages'),
            ];
        }
    }
}