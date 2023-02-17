<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->string('module_code', 7);
            $table->string('name', 64);
            $table->boolean('is_active', 1)->default(true);
            $table->boolean('is_in_menu', 4)->default(true);
            $table->string('display_order', 5);
            $table->timestamps();
            $table->softDeletes();
            $table->char('created_by', 36);
            $table->foreign('created_by')->references('id')->on('users')->nullable();
            $table->char('updated_by', 36);
            $table->foreign('updated_by')->references('id')->on('users')->nullable();
            $table->char('deleted_by', 36);
            $table->foreign('deleted_by')->references('id')->on('users')->nullable();
            $table->boolean('is_deleted', 1)->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
