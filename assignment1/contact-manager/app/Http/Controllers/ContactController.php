<?php

namespace App\Http\Controllers;

use App\Models\Contact; // Import the Contact model
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $contacts = Contact::all();
            return response()->json($contacts);
        }

        return view('contacts.index');
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'string|max:20',
        ]);

        try {
            $contact = Contact::create($validatedData);
            return response()->json(['success' => true, 'message' => 'Contact created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json($contact);
    }

    public function update(Request $request, Contact $contact)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email,' . $contact->id,
            'phone' => 'string|max:20',
        ]);

        $contact->update($validatedData);

        return response()->json(['success' => true, 'message' => 'Contact updated successfully']);
    }


    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(['message' => 'Contact deleted successfully']);
    }
}
