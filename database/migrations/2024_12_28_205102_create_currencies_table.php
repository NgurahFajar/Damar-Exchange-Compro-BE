<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->char('currency_code', 5)->primary();
            $table->string('currency_name');
            $table->double('buy_rate');
            $table->double('sell_rate');
            $table->integer('user_id');
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });

        // Menambahkan check constraint untuk currency_code
        DB::statement("ALTER TABLE currencies ADD CONSTRAINT check_currency_code CHECK (currency_code REGEXP '^[A-Z0-9]{1,5}$')");
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
