<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category_fields', function (Blueprint $table) {
            $table->integer('column_span')->default(6);
        });
    }

    public function down(): void
    {
        Schema::table('category_fields', function (Blueprint $table) {
            $table->dropColumn('column_span');
        });
    }
};
