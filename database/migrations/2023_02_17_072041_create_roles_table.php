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
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->string('name', 51);
            $table->string('description', 51);
            $table->boolean('is_active', 1)->default(true);
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
        Schema::dropIfExists('roles');
    }
};
