@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            @component('components.card')
                @slot('title')
                    {{ __('Hallo') }} {{ Auth::user()->username }}!
                @endslot
                    <ul class="nav flex-column nav-pills">
                        <li class="nav-item">
                            <a class="nav-link @if(Route::currentRouteName() === 'dash')active @endif" href="{{ route('dash') }}">{{ __('Deine Sammlungen') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Route::currentRouteName() === 'collections.create')active @endif" href="{{ route('collections.create') }}">{{ __('Sammlung hinzufügen') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('collections.index') }}">{{ __('Alle aktiven Sammlungen') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#">{{ __('Deine Beiträge') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                        </li>
                    </ul>
            @endcomponent
        </div>
        <div class="col-md-8">
            @yield('card-content')
        </div>
    </div>
</div>
@endsection
