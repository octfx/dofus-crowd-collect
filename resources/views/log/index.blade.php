@extends('layouts.auth')

@section('card-content')
    @component('components.card')
        @slot('title')
            {{ __('Deine Beiträge') }}
        @endslot
        <log-display
                :logs="{{ $logs }}"
                mode="personal"
        ></log-display>
    @endcomponent
@endsection