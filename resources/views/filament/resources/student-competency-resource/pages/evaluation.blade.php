<x-filament-panels::page>

<div class="flex">

  <div class="w-1/2 p-4">
    <x-filament::section>
        <x-slot name="heading">
            Competencies List
        </x-slot>    

        {{ $this->table }}
    </x-filament::section>
  </div>

  <div class="w-1/2 p-4">
    <x-filament::section>
        <x-slot name="heading">
            Students List
        </x-slot>
    
        <form wire:submit="submit">
            {{$this->form}}
            <x-filament::button class="mt-3" type="submit">
                Save
            </x-filament::button>
        </form>
    </x-filament::section>
  </div>
</div>
</x-filament-panels::page>
