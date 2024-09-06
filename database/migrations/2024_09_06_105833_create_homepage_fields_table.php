<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageFieldsTable extends Migration
{
    public function up()
    {
        Schema::create('homepage_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homepage_section_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('type');
            $table->json('options')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homepage_fields');
    }
}
