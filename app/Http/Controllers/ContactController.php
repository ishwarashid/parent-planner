<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Handle the contact form submission
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submit(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Send email using the Mailable class
            Mail::to(env('CONTACT_EMAIL', 'info@parentplanner.site'))
                ->send(new ContactFormMail(
                    $request->name,
                    $request->email,
                    $request->subject,
                    $request->message
                ));

            return redirect()->back()->with('success', 'Thank you for your message. We will get back to you soon.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sorry, there was an error sending your message. Please try again later.');
        }
    }
}