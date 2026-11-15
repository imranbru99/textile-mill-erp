<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen items-center justify-center bg-stone-100">
    <form method="post" action="{{ url('/desk/login') }}" class="w-full max-w-sm space-y-3 rounded border bg-white p-6">
        @csrf
        <h1 class="text-xl font-semibold">Buyer and supplier desk</h1>
        <input name="email" type="email" required class="w-full rounded border px-3 py-2" placeholder="Email">
        <input name="password" type="password" required class="w-full rounded border px-3 py-2" placeholder="Password">
        @if ($errors->any())<p class="text-sm text-red-700">{{ $errors->first() }}</p>@endif
        <button class="w-full rounded bg-stone-900 py-2 text-white">Sign in</button>
        <a class="block text-center text-sm" href="{{ url('/admin/login') }}">Mill staff login</a>
    </form>
</body>
</html>
