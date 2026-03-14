<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_formulas', function (Blueprint $table) {
            $table->dropColumn('expression');
            $table->renameColumn('result_column', 'result');
            $table->string('field_a', 100)->after('result_label');
            $table->string('operator', 10)->after('field_a');
            $table->string('field_b', 100)->after('operator');
        });
    }

    public function down(): void
    {
        Schema::table('business_formulas', function (Blueprint $table) {
            $table->dropColumn(['field_a', 'operator', 'field_b']);
            $table->renameColumn('result', 'result_column');
            $table->json('expression')->nullable()->after('result_label');
        });
    }
};
