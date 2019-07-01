<div class="card shadow-sm {{ $class ?? '' }}">
    <div class="card-body">
        <h4 class="mt-3 mb-4">{{ $title }}</h4>
        {{ $slot }}
    </div>
</div>