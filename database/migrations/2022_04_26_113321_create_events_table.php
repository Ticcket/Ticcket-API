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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string("title", 100);
            $table->text("description");
            $table->string("logo", 100)->nullable();
            // $table->string("location", 100);
            $table->foreignId('creator')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->date("start_at");
            $table->date("end_at");
            // $table->softDeletes();
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
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('creator');
        });

        Schema::dropIfExists('events');
    }
};
