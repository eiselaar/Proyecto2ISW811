<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('social_accounts', function (Blueprint $table) {
            $table->dropColumn('provider_id');
            $table->text('provider_token')->change();
            $table->text('provider_refresh_token')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('social_accounts', function (Blueprint $table) {
            $table->string('provider_id');
            $table->string('provider_token')->change();
            $table->string('provider_refresh_token')->nullable()->change();
        });
    }
};
