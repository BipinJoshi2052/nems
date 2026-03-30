<x-mail::message>
# Welcome to EduNepal, {{ $tenantName }}!

Your institution's platform is ready to be set up. 
Please click the button below to secure your account and configure your initial password. This link will expire in 48 hours.

<x-mail::button :url="$url">
Setup Account
</x-mail::button>

If you did not request this, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
