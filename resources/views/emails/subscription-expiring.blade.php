<div style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
    <h2 style="color: #333;">Hello {{ $tenantName }},</h2>
    
    <p style="font-size: 16px; color: #555; line-height: 1.5;">
        This is a friendly reminder that your <strong>{{ $planName }}</strong> {{ $type }} for your institution is set to expire on <strong>{{ $expiryDate }}</strong> (in 2 days).
    </p>

    <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <p style="margin: 0; color: #666;">
            To ensure uninterrupted service for your students and staff, please log in to your dashboard and renew or upgrade your subscription.
        </p>
    </div>

    <p style="text-align: center; margin-top: 30px;">
        <a href="http://{{ $subscription->tenant->id }}.{{ config('tenancy.central_domains')[0] }}/subscription" 
           style="background-color: #4F46E5; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            Renew Subscription
        </a>
    </p>

    <p style="font-size: 14px; color: #888; margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
        If you have already renewed, please ignore this email.<br>
        Thank you for choosing {{ config('app.name') }}.
    </p>
</div>
