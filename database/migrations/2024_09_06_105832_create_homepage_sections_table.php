<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('column_span')->default(12);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homepage_sections');
    }
}
