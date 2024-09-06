<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageContentsTable extends Migration
{
    public function up()
    {
        Schema::create('homepage_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homepage_section_id')->constrained()->onDelete('cascade');
            $table->string('language_code')->default('tr');
            $table->json('content')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homepage_contents');
    }
}
