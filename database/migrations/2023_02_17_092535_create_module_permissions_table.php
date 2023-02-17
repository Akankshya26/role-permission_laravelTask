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
        Schema::create('module_permissions', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->char('permission_id', 36);
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->char('module_id', 36);
            $table->foreign('module_id')->references('id')->on('modules');
            $table->boolean('add_access');
            $table->boolean('edit_access');
            $table->boolean('delete_access');
            $table->boolean('view_access');
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
        Schema::dropIfExists('module_permissions');
    }
};
