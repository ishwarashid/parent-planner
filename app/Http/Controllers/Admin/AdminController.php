<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use Illuminate\Http\Request;

// Import the Mail facade and your new Mailable classes (which we'll create below)
use Illuminate\Support\Facades\Mail;
use App\Mail\ProfessionalApproved;
use App\Mail\ProfessionalRejected;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function professionals()
    {
        // Added latest() to show the newest pending professionals first.
        $professionals = Professional::with('user')->where('approval_status', 'pending')->latest()->get();
        return view('admin.professionals.index', compact('professionals'));
    }

    public function showProfessional(Professional $professional)
    {
        return view('admin.professionals.show', compact('professional'));
    }

    public function approveProfessional(Professional $professional)
    {
        $professional->update(['approval_status' => 'approved']);

        // Send the approval email to the professional's user email
        Mail::to($professional->user->email)->send(new ProfessionalApproved($professional));

        return redirect()->route('admin.professionals.index')->with('success', 'Professional approved successfully.');
    }

    public function rejectProfessional(Professional $professional)
    {
        $professional->update(['approval_status' => 'rejected']);

        // Send the rejection email
        Mail::to($professional->user->email)->send(new ProfessionalRejected($professional));

        return redirect()->route('admin.professionals.index')->with('success', 'Professional rejected successfully.');
    }
}
