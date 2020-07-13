@extends('layouts.auth')

@section('card-content')
    @component('components.card')
        @slot('title')
            {{ __('Deine aktiven Sammlungen') }}
        @endslot
        <dashboard
                :public-mode="false"
        ></dashboard>
    @endcomponent
@endsection
