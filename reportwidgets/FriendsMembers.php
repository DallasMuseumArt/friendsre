<?php

namespace DMA\FriendsRE\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Rainlab\User\Models\User;
use DMA\FriendsRE\Models\RazorsEdge;

class FriendsMembers extends ReportWidgetBase
{
    public function render()
    {
        $friends = User::count();
        $partners = count(RazorsEdge::select('id')->groupBy('user_id')->get());
        $notPartners = $friends - $partners;

        $this->vars['totalFriends'] = number_format($friends);
        $this->vars['notPartners'] = number_format($notPartners);
        $this->vars['partners'] = number_format($partners);
        $this->vars['partnerPercent'] = round(($partners / $friends) * 100) . '%';

        return $this->makePartial('widget');
    }
}
