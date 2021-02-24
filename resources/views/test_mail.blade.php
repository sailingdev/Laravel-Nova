@component('mail::message')
# Revenue Driver Introduction

The body of your message.

@component('mail::button', ['url' => ''])
Hello Mona
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
