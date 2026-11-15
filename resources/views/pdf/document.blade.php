<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Noto Sans Bengali', sans-serif; font-size: 12px; }
        h1 { font-size: 18px; }
        table { width: 100%; border-collapse: collapse; }
        td { border-bottom: 1px solid #ddd; padding: 4px 0; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>নম্বর / Number: {{ $document->number ?? '' }}</p>
    <p>তারিখ / Date: {{ $document->document_date ?? '' }}</p>
    <p>অবস্থা / Status: {{ $document->status ?? '' }}</p>
    <p>মোট / Total: {{ $document->total_amount ?? '' }} {{ $document->currency_code ?? '' }}</p>
    <p>{{ $document->notes ?? '' }}</p>
</body>
</html>
