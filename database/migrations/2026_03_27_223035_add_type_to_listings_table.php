<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    if (!Schema::hasColumn('listings', 'type')) {
        Schema::table('listings', function (Blueprint $table) {
            $table->string('type')->default('location')->after('location');
        });
    }
}

public function down(): void
{
    Schema::table('listings', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}
};
