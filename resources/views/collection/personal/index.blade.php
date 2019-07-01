@extends('layouts.auth')

@section('card-content')
    @component('components.card')
        @slot('title')
            {{ __('Deine aktiven Sammlungen') }}
        @endslot
        <dashboard
                get-url="{{ route('api.collections.personal.index') }}"
                update-url="{{ route('api.logs.store') }}"
                delete-url="{{ route('api.collections.destroy', 'zzz') }}"
                api-key="{{ Auth::user()->api_token }}"
        ></dashboard>
    @endcomponent
@endsection
