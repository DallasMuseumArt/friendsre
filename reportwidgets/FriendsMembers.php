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

        $this->vars['friends'] = $friends;
        $this->vars['partners'] = $partners;
        $this->vars['notPartners'] = $friends - $partners;
        $this->vars['friendsPercentage'] = round(($this->vars['partners'] / $friends) * 100);
        return $this->makePartial('widget');
    }
}
