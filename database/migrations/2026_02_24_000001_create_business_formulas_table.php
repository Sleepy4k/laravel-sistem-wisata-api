<?php

use App\Models\Business;
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
        Schema::create('business_formulas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Business::class)->constrained()->cascadeOnDelete();
            $table->string('result_column', 100)->comment('Key name of the computed output column');
            $table->string('result_label', 150)->comment('Human-readable label for the result column');
            $table->json('expression')->comment('Ordered token list: [{type: field|operator|literal, value: ...}]');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_formulas');
    }
};
