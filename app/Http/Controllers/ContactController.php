<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email',
            'comment' => 'required|string',
        ]);

        try {
            // Create new contact record
            Contact::create($request->all());

            return redirect()->back()->with('success', 'Thank you for your message. We will get back to you soon!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Sorry, there was a problem submitting your message. Please try again.')
                ->withInput();
        }
    }
}