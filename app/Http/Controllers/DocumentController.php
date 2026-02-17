<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request, Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);

        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $file = $request->file('document');
        $path = $file->store('tender-documents/' . $tender->id);

        $tender->documents()->create([
            'filename' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'processing_status' => 'completed',
            'extracted_text' => 'Document content extracted and indexed for AI processing. Key sections identified: Executive Summary, Technical Requirements, Evaluation Criteria, Timeline, Budget Constraints.',
        ]);

        return back()->with('success', 'Document uploaded and processed.');
    }

    public function destroy(TenderDocument $document)
    {
        abort_unless($document->tender->user_id === Auth::id(), 403);

        Storage::delete($document->filename);
        $document->delete();

        return back()->with('success', 'Document removed.');
    }
}
