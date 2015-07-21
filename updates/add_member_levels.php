<?php namespace DMA\FriendsRE\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddMemberLevels extends Migration
{

    public function up()
    {   
        Schema::table('dma_friendsre_razors_edges', function($table)
        {   
            $table->integer('member_level');
        }); 

    }   

    public function down()
    {   
        Schema::table('dma_friendsre_razors_edges', function($table)
        {   
            $table->dropIndex('member_level');
        }); 
    }   

}
