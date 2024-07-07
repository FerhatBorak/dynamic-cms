<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('category_field_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_field_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('label')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->unique(['category_field_id', 'locale']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_field_translations');
    }
};
