<x-filament-panels::page>

<div class="flex">

  {{-- <div class="w-1/2 p-4">
    <x-filament::section>
        <x-slot name="heading">
            Competencies List
        </x-slot>    

        {{ $this->table }}
    </x-filament::section>
  </div> --}}

  <div class="p-4">
    <form wire:submit="submit">
        {{$this->form}}
        <x-filament::button class="mt-3" type="submit" wire:loading.class="opacity-50">
            Save
        </x-filament::button>
    </form>
  </div>
</div>
</x-filament-panels::page>
