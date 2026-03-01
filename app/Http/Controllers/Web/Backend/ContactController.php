<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $contacts = Contact::latest()->get();

            return DataTables::of($contacts)
                ->addIndexColumn()
                ->addColumn('status', function($data) {
                    return $data->status == Contact::STATUS['READ'] ? 
                        '<span class="badge bg-success">Read</span>' :
                        '<span class="badge bg-warning">Unread</span>';
                })
                ->addColumn('action', function($data){
                    return '
                        <button onclick="markRead('.$data->id.')" class="btn btn-info btn-sm">
                            Mark as Read
                        </button>
                    ';
                })
                ->addColumn('action', function($data){
                    return '
                        <button onclick="viewContact('.$data->id.')" 
                            class="btn btn-primary btn-sm">
                            View
                        </button>
                    ';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('backend.layout.contacts.index');
    }

    // Mark contact as read
    public function markRead($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => Contact::STATUS['READ']]);

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read'
        ]);
    }

    // Fetch single contact data for modal

    public function view($id)
    {
        $contact = Contact::findOrFail($id);

        // Add human-readable time
        $contact->time_ago = Carbon::parse($contact->created_at)->diffForHumans();

        return response()->json([
            'success' => true,
            'data' => $contact
        ]);
    }
}
