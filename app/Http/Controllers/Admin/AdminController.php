<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\User;
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

    public function users(Request $request)
    {
        // Start with all users
        $query = User::query();
        
        // Exclude professionals and admin users
        $query->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Professional');
        })->where('is_admin', '!=', 1);
        
        // Filter by main members vs invited members if specified
        if ($request->has('user_type')) {
            if ($request->user_type === 'main') {
                $query->where('invited_by', null);
            } elseif ($request->user_type === 'invited') {
                $query->where('invited_by', '!=', null);
            }
        }
        
        // Search by name or email if specified
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        // Eager load roles for better performance
        $users = $query->with('roles')->latest()->paginate(15);
        
        return view('admin.users.index', compact('users'));
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
