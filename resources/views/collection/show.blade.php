@extends('layouts.auth')

@section('card-content')
    @component('components.card')
        @slot('title')
            {{ $collection->user->username }}: {{ $collection->name }}
        @endslot
        <collection-single
            :id="{{ $collection->id }}"
        ></collection-single>
    @endcomponent
@endsection
