<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->boolean('is_default')->default(false)->after('is_active');
        });

        // Varsayılan dili ayarla (örneğin, İngilizce)
        DB::table('languages')
            ->where('code', 'en')
            ->update(['is_default' => true]);
    }

    public function down()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
};
