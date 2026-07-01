<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('iot', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('distance', 2);
            $table->float('ph', 2);
            $table->float('oxygen_concentration', 2);
            $table->float('oxygen_saturation', 2);
            $table->float('temperature', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot');
    }
};
