<x-filament-panels::page>
    <form wire:submit="submit">
        {{$this->form}}
        <x-filament::button class="mt-3" type="submit" wire:loading.class="opacity-50">
            Save
        </x-filament::button>
    </form>
</x-filament-panels::page>
