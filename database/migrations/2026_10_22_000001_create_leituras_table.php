<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leituras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('device_id', 64)->index();
            $table->decimal('cell_1', 10, 2);
            $table->decimal('cell_2', 10, 2);
            $table->decimal('cell_3', 10, 2);
            $table->decimal('cell_4', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leituras');
    }
};
