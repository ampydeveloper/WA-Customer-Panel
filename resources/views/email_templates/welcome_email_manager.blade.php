@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Welcome {{$user->first_name.' '. $user->last_name}}</h2>

    <p>Below are your login details</p>

    <p>Email:- {{$user->email}}</p>

    <p>Password:- {{$password}}</p>
</div>
@endsection