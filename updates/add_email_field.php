<?php namespace DMA\FriendsRE\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddEmailField extends Migration
{

    public function up()
    {   
        Schema::table('dma_friendsre_razors_edges', function($table)
        {   
            $table->integer('email');
        }); 
    }   

    public function down()
    {   
        Schema::table('dma_friendsre_razors_edges', function($table)
        {   
            $table->dropColumn('email');
        }); 
    }   

}
