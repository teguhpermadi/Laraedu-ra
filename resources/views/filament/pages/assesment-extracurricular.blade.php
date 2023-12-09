<x-filament-panels::page>
    <div class="p-4">

        <form wire:submit="submit" class="mb-4">
            {{$this->form}}
            {{-- <x-filament::button class="mt-3" type="submit" wire:loading.class="opacity-50">
                Check
            </x-filament::button> --}}
        </form>

        {{$this->table}}

    </div>
</x-filament-panels::page>
