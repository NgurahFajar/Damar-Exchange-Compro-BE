<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First backup any existing data if needed
        // $currencies = DB::table('currencies')->whereNotNull('icon_data')->get();

        // Schema::table('currencies', function (Blueprint $table) {
        //     // Drop the existing mediumtext column
        //     $table->dropColumn('icon_data');
        // });

        Schema::table('currencies', function (Blueprint $table) {
            // Add new MEDIUMBLOB column
            $table->binary('icon_data')->nullable()->after('currency_name');
            $table->string('icon_type', 100)->nullable()->after('icon_data');
        });

        // Change to MEDIUMBLOB explicitly
        DB::statement("ALTER TABLE currencies MODIFY icon_data MEDIUMBLOB");

        // Restore any backed up data if needed
        // foreach ($currencies as $currency) {
        //     if ($currency->icon_data) {
        //         DB::table('currencies')
        //             ->where('currency_code', $currency->currency_code)
        //             ->update(['icon_data' => $currency->icon_data]);
        //     }
        // }
    }

    public function down()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('icon_data');
            $table->mediumText('icon_data')->nullable()->after('currency_name');
        });
    }
};
