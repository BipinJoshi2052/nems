<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Stancl\Tenancy\Jobs\MigrateDatabase;
use App\Mail\TenantWelcomeMail;
use Illuminate\Support\Facades\Mail;

class ProvisionTenantJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $tenantId, public ?int $planId = null)
    {
    }

    public function handle(): void
    {
        $tenant = Tenant::find($this->tenantId);
        if (!$tenant) {
            \Log::error("Provisioning failed: Tenant {$this->tenantId} not found.");
            return;
        }

        \Log::info("Starting provisioning for Tenant: {$tenant->name} ({$tenant->id})");

        try {
            // 1. Create Physical Database
            \Log::info("Creating database for tenant {$tenant->id}");
            CreateDatabase::dispatchSync($tenant);

            // 2. Run General Migrations
            \Log::info("Running general migrations for tenant {$tenant->id}");
            MigrateDatabase::dispatchSync($tenant);

            // 3. Vertical Specific Migrations
            if ($tenant->vertical === 'montessori') {
                \Log::info("Running Montessori migrations for tenant {$tenant->id}");
                tenancy()->initialize($tenant);
                Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path' => 'database/migrations/tenant/montessori',
                    '--force' => true,
                ]);
                tenancy()->end();
            }

            // 4. Seed Roles and Create Default Admin User
            $subdomain = $tenant->subdomain ?? Str::slug($tenant->name);
            $adminEmail = $tenant->email;

            \Log::info("Initializing tenant context for role seeding and user creation.");
            tenancy()->initialize($tenant);
            
            $roleId = DB::table('roles')->where('name', 'school_admin')->value('id');
            if (!$roleId) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => 'school_admin',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $userId = DB::table('users')->insertGetId([
                'name' => 'Admin User',
                'email' => $adminEmail,
                'password' => Hash::make(Str::random(16)),
                'is_owner' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => 'App\Models\User',
                'model_id' => $userId,
            ]);

            tenancy()->end();

            // 5. Create Subdomain
            $domain = $subdomain . '.' . config('app.url_base', 'edu.localhost');
            \Log::info("Creating domain record: {$domain}");
            
            DB::table('domains')->insert([
                'domain' => $domain,
                'tenant_id' => $this->tenantId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 6. Create Subscription (Fetch plan from central DB)
            if ($this->planId) {
                $plan = \App\Models\Plan::find($this->planId);
                if ($plan) {
                    \Log::info("Creating subscription for plan: {$plan->name}");
                    \App\Models\Subscription::create([
                        'tenant_id' => $this->tenantId,
                        'plan_id' => $this->planId,
                        'status' => 'trial',
                        'trial_ends_at' => now()->addDays($plan->trial_days ?? 14),
                    ]);
                }
            }

            // 7. Sync Platform User 
            $exists = DB::table('platform_tenant_users')->where('email', $adminEmail)->exists();
            if (!$exists) {
                DB::table('platform_tenant_users')->insert([
                    'tenant_id' => $this->tenantId,
                    'email' => $adminEmail,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 8. Mark Tenant Trial (Enforcing State Machine)
            \Log::info("Transitioning tenant status to 'trial'");
            $tenant->transitionTo(\App\Models\Tenant::STATUS_TRIAL);

            // 9. Send Welcome Email
            try {
                \Log::info("Sending welcome email to {$adminEmail}");
                Mail::to($adminEmail)->send(new TenantWelcomeMail($tenant->name, $domain));
            } catch (\Exception $e) {
                \Log::warning("Welcome email failed for {$adminEmail}: " . $e->getMessage());
            }

            \Log::info("Provisioning completed successfully for Tenant: {$tenant->id}");

        } catch (\Exception $e) {
            \Log::error("Provisioning fatal error for Tenant {$this->tenantId}: " . $e->getMessage());
            \Log::error($e->getTraceAsString());
            // Optionally update tenant status to 'failed'
            DB::table('tenants')->where('id', $this->tenantId)->update(['status' => 'failed']);
            throw $e;
        }
    }
}
