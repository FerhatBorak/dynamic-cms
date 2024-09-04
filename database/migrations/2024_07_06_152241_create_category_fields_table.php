<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('field_type_id')->constrained()->onDelete('cascade');
            $table->json('type_specific_config')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('min_length')->nullable();
            $table->integer('max_length')->nullable();
            $table->integer('rows')->nullable();
            $table->string('min')->nullable();
            $table->string('max')->nullable();
            $table->string('step')->nullable();
            $table->date('min_date')->nullable();
            $table->date('max_date')->nullable();
            $table->string('allowed_file_types')->nullable();
            $table->integer('max_file_size')->nullable();
            $table->string('label')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->boolean('is_unique')->default(false);
            $table->json('validation_rules')->nullable();
            $table->json('conditional_logic')->nullable();
            $table->integer('column_span')->default(6);
            $table->json('js_events')->nullable();
            $table->string('default_value')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_fields');
    }
};
