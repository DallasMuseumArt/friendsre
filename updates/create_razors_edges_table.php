<?php namespace DMA\FriendsRE\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateRazorsEdgesTable extends Migration
{

    public function up()
    {
        Schema::create('dma_friendsre_razors_edges', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('razorsedge_id');
            $table->integer('member_id')->nullable();
            $table->timestamp('expires_on')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('zip')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dma_friendsre_razors_edges');
    }

}
