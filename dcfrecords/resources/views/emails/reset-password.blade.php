@extends('email-body')

@section('content')
<p>Hello {{$user->username}} ({{$user->first_name}} {{$user->last_name}}),</p>
<p>We received a request to reset your password.</p>
<p>Your new password is: <b>{{$password}}</b></p>
<p>We recommend that you login and change is as soon as possible.</p>
<p>Thanks,</p>
<p>DCF Records Team</p>
@endsection