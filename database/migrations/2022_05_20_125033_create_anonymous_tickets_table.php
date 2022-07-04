<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anonymous_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email');
            $table->string('token', 20);
            $table->boolean("scanned")->default(0);
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean("sent")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anonymous_tickets', function(Blueprint $table) {
            $table->dropForeign('event_id');
        });
        Schema::dropIfExists('anonymous_tickets');
    }
};
