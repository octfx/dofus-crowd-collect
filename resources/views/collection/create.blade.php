@extends('layouts.auth')

@section('card-content')
    @component('components.card')
        @slot('title')
            {{ __('Sammlung erstellen') }}
        @endslot
        <create-collection></create-collection>
    @endcomponent
@endsection
