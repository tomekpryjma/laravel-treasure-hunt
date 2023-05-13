@extends('layouts.app')

@section('content')

<h1>You're in the session for: {{ $gameSession->game->title }}</h1>

@endsection

@section('scripts')
<script type="module">
    console.log(Echo)
    window.Echo.channel('test')
    .listen('AnEvent', (e) => {
        console.log(e);
    });
</script>
@endsection
