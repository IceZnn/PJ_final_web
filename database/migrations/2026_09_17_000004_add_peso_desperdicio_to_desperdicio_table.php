<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('desperdicio', function (Blueprint $table) {
            $table->decimal('peso_desperdicio', 8, 2)->nullable()->after('maximo_desperdicio');
        });
    }

    public function down(): void
    {
        Schema::table('desperdicio', function (Blueprint $table) {
            $table->dropColumn('peso_desperdicio');
        });
    }
};
