<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('field_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('fields');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('field_templates');
    }
};
