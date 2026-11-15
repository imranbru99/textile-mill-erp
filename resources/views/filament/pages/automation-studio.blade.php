<x-filament-panels::page>
    <form wire:submit="save" class="mb-6 grid gap-3 md:grid-cols-4">
        <input wire:model="name" placeholder="Rule name" class="rounded border px-3 py-2" required>
        <input wire:model="trigger" placeholder="trigger.key" class="rounded border px-3 py-2" required>
        <input wire:model="threshold" placeholder="Threshold" class="rounded border px-3 py-2" required>
        <button type="submit" class="rounded bg-amber-600 px-3 py-2 text-white">Save rule</button>
    </form>
    <ul class="text-sm">
        @foreach ($rules as $rule)
            <li class="border-b py-2">{{ $rule->name }} · {{ $rule->trigger }} · threshold {{ $rule->threshold }} · {{ $rule->is_active ? 'active' : 'off' }}</li>
        @endforeach
    </ul>
</x-filament-panels::page>
