<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('category_fields', function (Blueprint $table) {
            $table->json('type_specific_config')->nullable()->after('field_type_id');
            $table->json('conditional_logic')->nullable()->after('validation_rules');
            $table->json('js_events')->nullable()->after('conditional_logic');
        });
    }

    public function down()
    {
        Schema::table('category_fields', function (Blueprint $table) {
            $table->dropColumn(['type_specific_config', 'conditional_logic', 'js_events']);
        });
    }
};
