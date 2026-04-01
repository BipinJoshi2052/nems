<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop constraints
        DB::statement('ALTER TABLE platform_audit_logs DROP CONSTRAINT IF EXISTS platform_audit_logs_admin_id_foreign');
        DB::statement('ALTER TABLE platform_tenant_users DROP CONSTRAINT IF EXISTS platform_tenant_users_user_id_foreign');
        
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS platform_admins_pkey CASCADE');
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_pkey CASCADE');
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS pk_users_uuid CASCADE');
        
        DB::statement('DROP INDEX IF EXISTS platform_admins_pkey');
        DB::statement('DROP INDEX IF EXISTS users_pkey');
        DB::statement('DROP INDEX IF EXISTS pk_users_uuid');

        // 2. Change column types
        DB::statement('ALTER TABLE users ALTER COLUMN id TYPE VARCHAR(255) USING id::varchar');
        DB::statement('ALTER TABLE platform_tenant_users ALTER COLUMN user_id TYPE VARCHAR(255) USING user_id::varchar');
        DB::statement('ALTER TABLE platform_audit_logs ALTER COLUMN admin_id TYPE VARCHAR(255) USING admin_id::varchar');
        DB::statement('ALTER TABLE platform_audit_logs ALTER COLUMN subject_id TYPE VARCHAR(255) USING subject_id::varchar');
        DB::statement('ALTER TABLE sessions ALTER COLUMN user_id TYPE VARCHAR(255) USING user_id::varchar');

        // 3. Restore Primary Key with a UNIQUE name
        DB::statement('ALTER TABLE users ADD CONSTRAINT pk_users_uuid PRIMARY KEY (id)');

        // 4. Restore Foreign Keys
        DB::statement('ALTER TABLE platform_audit_logs ADD CONSTRAINT platform_audit_logs_admin_id_foreign FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE SET NULL');
        
        $hasCol = DB::selectOne("SELECT 1 FROM information_schema.columns WHERE table_name = 'platform_tenant_users' AND column_name = 'user_id'");
        if ($hasCol) {
            DB::statement('ALTER TABLE platform_tenant_users ADD CONSTRAINT platform_tenant_users_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
        }
    }

    private function dropFKIfExists($table, $fk)
    {
        $exists = DB::selectOne("SELECT 1 FROM pg_constraint WHERE conname = ?", [$fk]);
        if ($exists) {
            DB::statement("ALTER TABLE $table DROP CONSTRAINT $fk");
        }
    }

    public function down(): void {}
};
