<?php

use App\Models\Os;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conta_recebers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Os::class)->constrained()->cascadeOnDelete();
            $table->integer('valor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conta_recebers');
    }
};
