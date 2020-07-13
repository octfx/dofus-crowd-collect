@extends('layouts.auth')

@section('card-content')
    @component('components.card')
        @slot('title')
            {{ __('Alle öffentlichen Sammlungen') }}
        @endslot
        <dashboard
                :public-mode="true"
        ></dashboard>
    @endcomponent
@endsection
