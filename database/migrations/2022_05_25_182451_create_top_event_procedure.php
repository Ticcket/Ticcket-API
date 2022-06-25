<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "

        CREATE PROCEDURE IF NOT EXISTS `get_top_events`(IN `mlimit` SMALLINT(10) UNSIGNED)
        BEGIN

        SELECT `events`.*, AVG(`feedbacks`.rating) As rating FROM `events`
        INNER JOIN `feedbacks` ON `events`.id = `feedbacks`.event_id
        GROUP By `feedbacks`.event_id
        ORDER BY rating DESC LIMIT `mlimit`;

        END;
        ";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "DROP PROCEDURE IF EXISTS get_top_events";
        DB::unprepared($sql);
    }
};
