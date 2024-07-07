<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('category_fields', function (Blueprint $table) {
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
            $table->string('default_value')->nullable();
            $table->integer('order')->default(0);

        });
    }

    public function down()
    {
        Schema::table('category_fields', function (Blueprint $table) {
            $table->dropColumn([
                'min_length', 'max_length', 'rows', 'min', 'max', 'step',
                'min_date', 'max_date', 'allowed_file_types', 'max_file_size',
                'label', 'placeholder', 'help_text', 'is_unique', 'validation_rules',
                'default_value', 'order'
            ]);
        });
    }
};
