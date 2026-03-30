<x-mail::message>
# Verify your email for EduNepal

Here is your 6-digit verification code:

<x-mail::panel>
# {{ $otp }}
</x-mail::panel>

This code will expire in 10 minutes.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
