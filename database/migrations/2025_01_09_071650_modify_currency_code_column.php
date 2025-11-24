<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop existing constraint if it exists
        // DB::statement('ALTER TABLE currencies DROP CONSTRAINT IF EXISTS check_currency_code');

        // Add new constraint that allows special formats
        // DB::statement("
        //     ALTER TABLE currencies
        //     ADD CONSTRAINT check_currency_code
        //     CHECK (currency_code REGEXP '^[A-Z]{3}(p/w|_?[0-9]+)?$')
        // ");
    }

    public function down()
    {
        // DB::statement('ALTER TABLE currencies DROP CONSTRAINT IF EXISTS check_currency_code');

        // // Restore original constraint if needed
        // DB::statement("
        //     ALTER TABLE currencies
        //     ADD CONSTRAINT check_currency_code
        //     CHECK (currency_code REGEXP '^[A-Z0-9]+$')
        // ");
    }
};
