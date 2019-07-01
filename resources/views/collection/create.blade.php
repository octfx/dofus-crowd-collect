@extends('layouts.auth')

@section('card-content')
    @component('components.card')
        @slot('title')
            {{ __('Sammlung erstellen') }}
        @endslot
        <create-collection
                post-url="{{ route('api.collections.store') }}"
                api-key="{{ Auth::user()->api_token }}"
        ></create-collection>
    @endcomponent
@endsection
