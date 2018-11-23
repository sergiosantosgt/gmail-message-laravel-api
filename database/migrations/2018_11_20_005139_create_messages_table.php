<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('message_id');
            $table->string('api_message_id')->nullable()->unique();
            $table->string('subject', 255)->nullable();
            $table->text('message')->nullable();
            $table->mediumText('complete_message')->nullable();

            $table->timestamps();
            
            $table->integer('client_id')->unsigned(); 
            
            $table->foreign('client_id')
                  ->references('client_id')
                  ->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
