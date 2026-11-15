<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-3">
        @foreach ($columns as $status => $issues)
            <section class="rounded-lg border p-3">
                <h2 class="mb-2 font-semibold capitalize">{{ $status }}</h2>
                @foreach ($issues as $issue)
                    <article class="mb-2 rounded border p-2 text-sm">
                        <div class="font-medium">{{ $issue->number }} · {{ $issue->severity }}</div>
                        <div>{{ $issue->issue_type }}</div>
                        <p class="text-xs">{{ $issue->recommendation }}</p>
                    </article>
                @endforeach
            </section>
        @endforeach
    </div>
    <h2 class="mt-6 font-semibold">Automation log</h2>
    <ul class="mt-2 text-sm">
        @foreach ($logs as $log)
            <li>{{ $log->created_at }} · {{ $log->action }} · issue {{ $log->mill_issue_id }}</li>
        @endforeach
    </ul>
</x-filament-panels::page>
