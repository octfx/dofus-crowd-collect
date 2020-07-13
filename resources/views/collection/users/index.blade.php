@extends('layouts.auth')

@section('card-content')
    @component('components.card')
        @slot('title')
            {{ __('Benutzer mit öffentlichen Sammlungen') }}
        @endslot
        <div class="card-columns">
            @foreach($users as $user)
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('collections.show.user', $user->getRouteKey() ?? '') }}">{{ $user->username }}
                            ({{ $user->collections_public_count }})</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endcomponent
@endsection
