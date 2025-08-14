<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Visitation;
use App\Models\Expense;
use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VisitationsExport;
use App\Exports\ExpensesExport;

class ReportController extends Controller
{
    public function __construct()
    {
        // This single line connects all controller methods to their corresponding policy methods.
        $this->authorizeResource(\App\Http\Controllers\ReportController::class, 'report');
    }

    public function index()
    {
        $children = auth()->user()->children;
        return view('reports.index', compact('children'));
    }

    public function generateExpenseReport(Request $request)
    {
        $request->validate([
            'child_id' => 'nullable|exists:children,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category' => 'nullable|string',
            'status' => 'nullable|string|in:pending,paid,disputed',
            'format' => 'required|in:pdf,csv',
        ]);

        // Get all family member IDs to include expenses from all family members
        $familyMemberIds = auth()->user()->getFamilyMemberIds();
        
        // Get children IDs for the family
        $childrenIds = Child::whereIn('user_id', $familyMemberIds)->pluck('id');
        
        // Start with all expenses for family children
        $expenses = Expense::whereIn('child_id', $childrenIds);

        if ($request->child_id) {
            $expenses->where('child_id', $request->child_id);
        }

        if ($request->start_date) {
            $expenses->where('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $expenses->where('created_at', '<=', $request->end_date);
        }

        if ($request->category) {
            $expenses->where('category', $request->category);
        }

        if ($request->status) {
            $expenses->where('status', $request->status);
        }

        $expenses = $expenses->get();

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.expenses_pdf', compact('expenses'));
            return $pdf->download('expense_report_' . now()->format('Ymd_His') . '.pdf');
        } else {
            return Excel::download(new ExpensesExport($expenses), 'expense_report_' . now()->format('Ymd_His') . '.csv');
        }
    }

    public function generateCalendarReport(Request $request)
    {
        $request->validate([
            'child_id' => 'nullable|exists:children,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'nullable|in:event,visitation,both',
            'assigned_to' => 'nullable|exists:users,id',
            'format' => 'required|in:pdf,csv',
        ]);

        $type = $request->type ?? 'both';
        $events = collect();
        $visitations = collect();

        // Get events if requested
        if ($type === 'event' || $type === 'both') {
            $events = auth()->user()->events();
            
            if ($request->child_id) {
                $events->where('child_id', $request->child_id);
            }
            
            if ($request->start_date) {
                $events->where('start', '>=', $request->start_date);
            }
            
            if ($request->end_date) {
                $events->where('start', '<=', $request->end_date);
            }
            
            if ($request->assigned_to) {
                $events->where('assigned_to', $request->assigned_to);
            }
            
            $events = $events->get();
        }

        // Get visitations if requested
        if ($type === 'visitation' || $type === 'both') {
            $visitations = auth()->user()->visitations();
            
            if ($request->child_id) {
                $visitations->where('child_id', $request->child_id);
            }
            
            if ($request->start_date) {
                $visitations->where('date_start', '>=', $request->start_date);
            }
            
            if ($request->end_date) {
                $visitations->where('date_end', '<=', $request->end_date);
            }
            
            if ($request->assigned_to) {
                $visitations->where('parent_id', $request->assigned_to);
            }
            
            $visitations = $visitations->get();
        }

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.calendar_pdf', compact('events', 'visitations', 'type'));
            return $pdf->download('calendar_report_' . now()->format('Ymd_His') . '.pdf');
        } else {
            // For CSV, use the new export class
            return Excel::download(new \App\Exports\CalendarReportExport($events, $visitations, $type), 'calendar_report_' . now()->format('Ymd_His') . '.csv');
        }
    }
}
