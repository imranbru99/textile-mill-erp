<x-filament-widgets::widget>
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900">Sales orders <strong>{{ $orders }}</strong></div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900">Yarn lots <strong>{{ $yarnLots }}</strong></div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900">Open issues <strong>{{ $openIssues }}</strong></div>
    </div>
</x-filament-widgets::widget>
