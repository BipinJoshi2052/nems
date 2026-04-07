<x-mail::message>
# Welcome to {{ $tenantName }}, {{ $name }}!

You have been invited to join the administrative platform of {{ $tenantName }}. 

To get started, please click the button below to set up your account and choose a secure password.

<x-mail::button :url="$url">
Set Up Account
</x-mail::button>

**Note:** This invitation link will expire in 48 hours for security reasons.

If you did not expect this invitation, you can safely ignore this email.

Thanks,<br>
{{ $tenantName }} Team
</x-mail::message>
