<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leituras', function (Blueprint $table) {
            $table->decimal('cell_1', 10, 2)->nullable()->change();
            $table->decimal('cell_2', 10, 2)->nullable()->change();
            $table->decimal('cell_3', 10, 2)->nullable()->change();
            $table->decimal('cell_4', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('leituras', function (Blueprint $table) {
            $table->decimal('cell_1', 10, 2)->nullable(false)->change();
            $table->decimal('cell_2', 10, 2)->nullable(false)->change();
            $table->decimal('cell_3', 10, 2)->nullable(false)->change();
            $table->decimal('cell_4', 10, 2)->nullable(false)->change();
        });
    }
};
