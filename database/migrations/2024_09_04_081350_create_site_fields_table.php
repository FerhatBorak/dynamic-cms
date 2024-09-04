<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('site_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique();
            $table->string('label');
            $table->string('type');
            $table->integer('column')->default(12); // 12 full width anlamına gelir
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_fields');
    }
};
