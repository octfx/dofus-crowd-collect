@extends('layouts.auth')

@section('card-content')
    @component('components.card')
        @slot('title')
            {{ __('Sammlung erstellen') }}
        @endslot
        <create-collection
                post-url="{{ route('api.collections.store') }}"
                search-url="{{ route('api.resource.search') }}"
        ></create-collection>
    @endcomponent
@endsection
