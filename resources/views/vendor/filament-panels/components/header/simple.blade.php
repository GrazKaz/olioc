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

            @if (session('account_message'))
                <div class="text-red-600 mb-2">
                    {{ session('account_message') }}
                </div>
            @endif
        </p>
    @endif
</header>
