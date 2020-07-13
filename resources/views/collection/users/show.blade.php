@extends('layouts.auth')

@section('card-content')
    @component('components.card')
        @slot('title')
            Sammlugen von {{ $user->username }}
        @endslot
        <collection-user
            name="{{ $user->name_slug }}"
        ></collection-user>
    @endcomponent
@endsection
