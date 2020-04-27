@component('mail::message')
# Welcome to {{ config('app.name') }}

You can create your profile by clicking the button.

@component('mail::button', ['url' => $url])
Create profile
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
