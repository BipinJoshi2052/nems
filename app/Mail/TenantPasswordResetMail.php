<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $token, public string $email, public string $tenantName)
    {
    }

    public function build()
    {
        $link = route('tenant.password.reset', ['token' => $this->token, 'email' => $this->email]);

        return $this->subject(__('Password Reset Request — :name', ['name' => $this->tenantName]))
            ->view('emails.tenant-password-reset')
            ->with([
                'link' => $link,
                'tenantName' => $this->tenantName,
            ]);
    }
}
