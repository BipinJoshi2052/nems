<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription, public string $type)
    {
    }

    public function build()
    {
        $days = 2;
        $subject = $this->type === 'trial' 
            ? "Your Trial expires in {$days} days" 
            : "Your Subscription expires in {$days} days";

        return $this->subject($subject)
            ->view('emails.subscription-expiring')
            ->with([
                'tenantName' => $this->subscription->tenant->name,
                'expiryDate' => $this->type === 'trial' 
                    ? $this->subscription->trial_ends_at->format('M d, Y') 
                    : $this->subscription->expires_at->format('M d, Y'),
                'planName' => $this->subscription->plan->name,
                'type' => $this->type,
            ]);
    }
}
