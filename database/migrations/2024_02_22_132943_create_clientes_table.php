<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('sexo', ['M', 'F']);
            $table->date('data_nascimento')->nullable();
            $table->string('cpf')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('telefone_celular')->nullable();
            $table->string('telefone_fixo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
