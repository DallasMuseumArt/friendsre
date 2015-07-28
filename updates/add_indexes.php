<?php namespace DMA\FriendsRE\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddIndexesTable extends Migration
{

    public function up()
    {   
        Schema::table('dma_friendsre_razors_edges', function($table)
        {   
            $table->index('user_id');
        }); 

    }    
}
