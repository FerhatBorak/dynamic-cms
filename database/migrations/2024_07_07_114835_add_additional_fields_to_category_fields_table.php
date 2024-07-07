<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('category_fields', function (Blueprint $table) {
            $table->string('label')->after('name')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->boolean('is_unique')->default(false);
            $table->string('min')->nullable();
            $table->string('max')->nullable();
            $table->string('step')->nullable();
            $table->text('default_value')->nullable();
            $table->json('validation_rules')->nullable();
            $table->integer('order')->default(0);
        });
    }

    public function down()
    {
        Schema::table('category_fields', function (Blueprint $table) {
            $table->dropColumn([
                'label', 'placeholder', 'help_text', 'is_unique',
                'min', 'max', 'step', 'default_value', 'validation_rules', 'order'
            ]);
        });
    }
};
