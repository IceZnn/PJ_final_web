<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desperdicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuario')->onDelete('cascade');
            $table->text('cardapio');
            $table->string('periodo');
            $table->json('salas');
            $table->decimal('quantidade_preparada', 8, 2);
            $table->unsignedTinyInteger('maximo_desperdicio');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desperdicio');
    }
};