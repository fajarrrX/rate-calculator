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
            $table->string('symbol_after_business_price')->nullable()->after('decimal_places');
            $table->string('symbol_after_personal_price')->nullable()->after('symbol_after_business_price');
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
            $table->dropColumn('symbol_after_business_price');
            $table->dropColumn('symbol_after_personal_price');
        });
    }
};
