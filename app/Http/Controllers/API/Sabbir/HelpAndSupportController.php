<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Term;
use Illuminate\Http\Request;

class HelpAndSupportController extends Controller
{
     // Store Contact
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = auth()->user(); // API user

        $contact = Contact::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'status'  => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact submitted successfully',
            'data' => $contact->load('user')
        ], 201);
    }

    // Get Active FAQs (Frontend)
    public function getFaqs()
    {
        $faqs = Faq::where('status', 1)
                    ->orderBy('priority', 'asc')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $faqs
        ]);
    }

    // Get Active Terms 
    public function getTerms()
    {
        $terms = Term::where('status', 1) // active terms
            ->orderBy('priority', 'desc') // priority descending
            ->select('id', 'title', 'description', 'priority', 'status', 'updated_at') // include updated_at
            ->get();

        return response()->json([
            'success' => true,
            'data' => $terms
        ]);
    }
}
