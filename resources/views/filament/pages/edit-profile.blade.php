<x-filament-panels::page>
    <form class="grid gap-y-6" wire:submit="saveProfile">
        {{ $this->profileForm }}

        <x-filament::actions :alignment="$this->alignActions()" :actions="$this->getProfileFormActions()" />
    </form>

    <div class="grid gap-y-6">
        {{ $this->locationForm }}
    </div>

    <form class="grid gap-y-6" wire:submit="saveAddress">
        {{ $this->addressForm }}

        <x-filament::actions :alignment="$this->alignActions()" :actions="$this->getAddressFormActions()" />
    </form>

    <form class="grid gap-y-6" wire:submit="savePass">
        {{ $this->passwordForm }}

        <x-filament::actions :alignment="$this->alignActions()" :actions="$this->getPasswordFormActions()" />
    </form>
</x-filament-panels::page>
