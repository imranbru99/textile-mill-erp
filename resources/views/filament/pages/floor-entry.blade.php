<x-filament-panels::page>
    <form wire:submit="post" class="grid max-w-xl gap-3 text-lg">
        <select wire:model="machine_id" class="rounded border px-3 py-3" required>
            <option value="">Ring frame</option>
            @foreach ($machines as $machine)
                <option value="{{ $machine->id }}">{{ $machine->code }} · {{ $machine->spindle_count }} spindles</option>
            @endforeach
        </select>
        <input wire:model="rpm" class="rounded border px-3 py-3" placeholder="RPM">
        <input wire:model="tpi" class="rounded border px-3 py-3" placeholder="TPI">
        <input wire:model="count_ne" class="rounded border px-3 py-3" placeholder="Count Ne">
        <input wire:model="hours" class="rounded border px-3 py-3" placeholder="Hours">
        <input wire:model="efficiency" class="rounded border px-3 py-3" placeholder="Efficiency %">
        <input wire:model="input_kg" class="rounded border px-3 py-3" placeholder="Input kg">
        <button class="rounded bg-amber-700 px-4 py-3 text-white">Post shift</button>
    </form>
</x-filament-panels::page>
