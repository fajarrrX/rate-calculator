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
        Schema::table('countries', function (Blueprint $table) {
            $table->boolean('hide_package_opt_en')->default(0)->after('symbol_after_personal_price');
            $table->boolean('hide_package_opt_local')->default(0)->after('hide_package_opt_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('hide_package_opt_en');
            $table->dropColumn('hide_package_opt_local');
        });
    }
};
