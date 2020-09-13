@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Hello {{$name}}</h2>

    <p>Please <a href="{{$verificationLink}}" target="_blank">click here</a> to change your password or directly copy and paste below link in new tab.</p>

    <p>{{$verificationLink}}</p>
</div>
@endsection
