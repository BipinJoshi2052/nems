<x-mail::message>
# Registration Successful: {{ $tenantName }}

Congratulations! Your portal has been provisioned and is now live.

You can access your dedicated institution portal here:

<x-mail::panel>
**Portal URL:** [http://{{ $domainUrl }}](http://{{ $domainUrl }})
</x-mail::panel>

<x-mail::button :url="$url">
Go To Portal
</x-mail::button>

We are thrilled to have you onboard.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
