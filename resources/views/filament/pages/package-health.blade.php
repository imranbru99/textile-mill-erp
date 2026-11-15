<x-filament-panels::page>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left"><th class="py-2">Package</th><th>Version</th><th>Status</th><th>Note</th></tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr class="border-t">
                    <td class="py-2 font-mono">{{ $row['name'] }}</td>
                    <td>{{ $row['version'] }}</td>
                    <td>{{ $row['status'] }}</td>
                    <td>{{ $row['note'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament-panels::page>
