@props([
    'heading' => null,
    'logo' => true,
    'subheading' => null,
])

<header class="fi-simple-header">
    @if ($logo)
        <x-filament-panels::logo />
    @endif

    @if (filled($heading))
        <h1 class="fi-simple-header-heading">
            {{ $heading }}
        </h1>
    @endif

    @if (filled($subheading))
        <p class="fi-simple-header-subheading">
            {{ $subheading }}

            @if (session('message'))
                <x-message type="{{ session('message.type') }}">{{ session('message.text') }}</x-message>
            @endif
        </p>
    @endif
</header>
