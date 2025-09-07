<?php

use App\Enums\FieldInputType;
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
        Schema::create('business_fields', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Business::class)->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('label', 150);
            $table->enum('type', FieldInputType::toArray());
            $table->json('options')->nullable();
            $table->json('validation_rules')->nullable();
            $table->string('placeholder', 200)->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_fields');
    }
};
