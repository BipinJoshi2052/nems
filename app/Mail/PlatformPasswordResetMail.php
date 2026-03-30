<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlatformPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $token, public string $email)
    {
    }

    public function build()
    {
        $link = route('platform.password.reset', ['token' => $this->token, 'email' => $this->email]);

        return $this->subject(__('Platform Password Reset Request'))
            ->view('emails.platform-password-reset')
            ->with([
                'link' => $link,
            ]);
    }
}
