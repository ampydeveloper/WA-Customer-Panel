@extends('layouts.mail')

@section('content')
<div class="container">
    <h2>Welcome {{$name}}</h2>

    <p>Please <a href="{{$verificationLink}}" target="_blank">click here</a> to confirm your account or directly copy and paste below link in new tab.</p>

    <p><a href="{{$verificationLink}}">{{$verificationLink}}</a></p>
</div>
@endsection
