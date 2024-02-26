<?php

use App\Models\Cargo;
use App\Models\Escolaridade;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('sexo', ['M', 'F']);
            $table->date('data_nascimento');
            $table->string('cpf');
            $table->string('telefone_celular')->nullable();
            $table->string('telefone_fixo')->nullable();
            $table->foreignIdFor(Cargo::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Escolaridade::class)->constrained()->cascadeOnDelete();
            $table->string('email')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
