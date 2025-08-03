<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function professionals()
    {
        $professionals = Professional::with('user')->where('approval_status', 'pending')->get();
        return view('admin.professionals.index', compact('professionals'));
    }

    public function showProfessional(Professional $professional)
    {
        return view('admin.professionals.show', compact('professional'));
    }

    public function approveProfessional(Professional $professional)
    {
        $professional->update(['approval_status' => 'approved']);
        // TODO: Add notification to the professional

        return redirect()->route('professional.pricing')->with('success', 'Professional approved successfully.');
    }

    public function rejectProfessional(Professional $professional)
    {
        $professional->update(['approval_status' => 'rejected']);
        // TODO: Add notification to the professional

        return redirect()->route('admin.professionals.index')->with('success', 'Professional rejected successfully.');
    }
}