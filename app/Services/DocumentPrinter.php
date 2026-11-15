<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Symfony\Component\HttpFoundation\Response;

final class DocumentPrinter
{
    public function download(Model $document, string $title): Response
    {
        return UnicodePdf::preset('bengali')
            ->loadView('pdf.document', [
                'title' => $title,
                'document' => $document,
                'company' => $document->getAttribute('company_id'),
            ])
            ->setPaper('a4')
            ->download($title.'-'.$document->getAttribute('number').'.pdf');
    }
}
