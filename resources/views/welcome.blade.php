//permissionmodule
$table->uuid('id', 36)->primary();
$table->char('permission_id', 36);
$table->foreign('permisson_id')->references('id')->on('permissions');
$table->char('module_id', 36);
$table->foreign('module_id')->references('id')->on('modules');
$table->boolean('add_access');
$table->boolean('edit_access');
$table->boolean('delete_access');
$table->boolean('view_access');
$table->timestamps();
$table->softDeletes();
$table->char('created_by', 36);
$table->foreign('created_by')->references('id')->on('users');
$table->char('updated_by', 36);
$table->foreign('updated_by')->references('id')->on('users');
$table->char('deleted_by', 36);
$table->foreign('deleted_by')->references('id')->on('users');
$table->boolean('is_deleted', 1)->default(false);
//permission role
$table->char('role_id', 36);
$table->foreign('role_id')->references('id')->on('roles');
$table->char('permission_id', 36);
$table->foreign('permission_id')->references('id')->on('permissions');
$table->boolean('is_active', 1)->default(true);
$table->timestamps();

//role user
$table->char('role_id', 36);
$table->foreign('role_id')->references('id')->on('roles');
$table->char('user_id', 36);
$table->foreign('user_id')->references('id')->on('users');
$table->boolean('is_active', 1)->default(true);
$table->timestamps();
