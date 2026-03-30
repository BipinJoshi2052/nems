<div style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
    <h2 style="color: #333;">{{ __('Platform Password Reset') }}</h2>
    
    <p style="font-size: 16px; color: #555; line-height: 1.5;">
        {{ __('You are receiving this email because we received a password reset request for your platform account.') }}
    </p>

    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ $link }}" 
           style="background-color: #0d6efd; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            {{ __('Reset Password') }}
        </a>
    </p>

    <p style="font-size: 14px; color: #777;">
        {{ __('This password reset link will expire in 60 minutes.') }}
    </p>

    <p style="font-size: 14px; color: #888; margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
        {{ __('If you did not request a password reset, no further action is required.') }}
    </p>
</div>
