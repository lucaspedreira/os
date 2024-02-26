<?php

use App\Models\Cliente;
use App\Models\Funcionario;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('os', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Funcionario::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Cliente::class)->constrained()->cascadeOnDelete();
            $table->text('descricao');
            $table->dateTime('data');
            $table->enum('status', ['aberto', 'fechado', 'cancelado']);
            $table->integer('valor_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('os');
    }
};
