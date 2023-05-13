@extends('layouts.app')

@section('content')

@if ($errors->any())
     @foreach ($errors->all() as $error)
         <div>{{$error}}</div>
     @endforeach
 @endif

<h1>Lobby for: {{ $gameSession->game->title }}</h1>

<form action="{{ route('game-session.attempt-access', ['sessionCode' => $gameSession->session_code]) }}" method="POST">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="access_code">Access code</label>
        <input type="text" name="access_code">
        <button type="submit">Access</button>
    </div>
</form>
@endsection