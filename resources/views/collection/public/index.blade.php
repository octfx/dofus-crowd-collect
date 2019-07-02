@extends('layouts.auth')

@section('card-content')
    @component('components.card')
        @slot('title')
            {{ __('Alle öffentlichen Sammlungen') }}
        @endslot
        <dashboard
                get-url="{{ route('api.collections.index') }}"
                update-url="{{ route('api.logs.store') }}"
                api-key="{{ Auth::user()->api_token }}"
                :public-mode="true"
        ></dashboard>
    @endcomponent
@endsection