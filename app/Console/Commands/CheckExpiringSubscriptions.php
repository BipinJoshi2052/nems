<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiringMail;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckExpiringSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expiring';
    protected $description = 'Find tenants with trial or subscription expiring in 2 days and notify them.';

    public function handle()
    {
        $targetDate = now()->addDays(2)->toImmutable();

        // 1. Check trials expiring in 2 days
        Subscription::where('status', 'trial')
            ->whereDate('trial_ends_at', $targetDate->toDateString())
            ->with(['tenant', 'plan'])
            ->get()
            ->each(function ($subscription) {
                Mail::to($subscription->tenant->email)->queue(new SubscriptionExpiringMail($subscription, 'trial'));
                $this->info("Notified {$subscription->tenant->name} about trial expiry.");
            });

        // 2. Check active subscriptions expiring in 2 days
        Subscription::where('status', 'active')
            ->whereDate('expires_at', $targetDate->toDateString())
            ->with(['tenant', 'plan'])
            ->get()
            ->each(function ($subscription) {
                Mail::to($subscription->tenant->email)->queue(new SubscriptionExpiringMail($subscription, 'subscription'));
                $this->info("Notified {$subscription->tenant->name} about subscription expiry.");
            });

        $this->info('Expiry check completed.');
    }
}
