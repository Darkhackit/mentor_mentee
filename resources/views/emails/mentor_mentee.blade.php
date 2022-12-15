<x-mail::message>
# Dear {{$mentee->name}}

 Your Mentor for the year 2023 is <strong>{{$mentee->mentor->name}}</strong>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
