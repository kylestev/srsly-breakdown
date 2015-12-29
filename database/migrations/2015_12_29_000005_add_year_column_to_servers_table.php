<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddYearColumnToServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->integer('year')->after('port')->unsigned()->default(2014);

            // switch from unique ip column to having a composite unique key of year and ip
            $table->dropUnique('servers_ip_unique');
            $table->unique(['ip', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('year');

            // switch back to the old unique index
            $table->dropUnique('servers_ip_year_unique');
            $table->unique('ip');
        });
    }
}
