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
        // Step 1: Authorize this action. Only specific users should run full family reports.
        // You should create this permission and assign it to the 'parent' or 'admin' role.
        // $this->authorize('generate-family-reports');

        $request->validate([
            'child_id' => 'nullable|exists:children,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category' => 'nullable|string',
            'status' => 'nullable|string|in:pending,paid,disputed',
            'format' => 'required|in:pdf,csv',
        ]);

        // Step 2: Revert to the original logic to get ALL family expenses.
        $familyMemberIds = auth()->user()->getFamilyMemberIds();
        $childrenIds = Child::whereIn('user_id', $familyMemberIds)->pluck('id');

        // Start with a base query for all expenses related to the family's children
        $query = Expense::with(['child', 'payer', 'splits.user', 'confirmations'])
            ->whereIn('child_id', $childrenIds);

        // Step 3: Apply the user-selected filters from the form
        if ($request->child_id) {
            $query->where('child_id', $request->child_id);
        }
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
            // $query->where('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
            // $query->where('created_at', '<=', $request->end_date);
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $expenses = $query->latest()->get();

        // Step 4: Generate the report in the requested format
        if ($request->format === 'pdf') {
            // The PDF can remain as is, as it can handle nested loops for splits easily.
            $pdf = Pdf::loadView('reports.expenses_pdf', compact('expenses'));
            return $pdf->download('expense_report_' . now()->format('Ymd_His') . '.pdf');
        } else {
            // For CSV, we pass the collection to our updated export class.
            return Excel::download(new ExpensesExport($expenses), 'expense_report_' . now()->format('Ymd_His') . '.csv');
        }
    }

    public function generateCalendarReport(Request $request)
    {
        // Authorize this action for security
        // $this->authorize('generate-family-reports');

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

        // Get all family member and children IDs
        $familyMemberIds = auth()->user()->getFamilyMemberIds();
        $childrenIds = Child::whereIn('user_id', $familyMemberIds)->pluck('id');

        // Get events if requested
        if ($type === 'event' || $type === 'both') {
            // === THIS IS THE FIX ===
            // Start by finding all events created BY ANY FAMILY MEMBER.
            // This is the correct way to scope events to the family.
            $query = Event::whereIn('user_id', $familyMemberIds)
                // Eager load relationships for performance
                ->with(['child', 'assignee.roles', 'user']);

            // Now, apply the filters ON TOP of the correct base data set.
            if ($request->child_id) {
                $query->where('child_id', $request->child_id);
            }
            if ($request->start_date) {
                $query->whereDate('start', '>=', $request->start_date);
            }
            if ($request->end_date) {
                $query->whereDate('start', '<=', $request->end_date);
            }
            if ($request->assigned_to) {
                $query->where('assigned_to', $request->assigned_to);
            }
            $events = $query->get();
        }

        // Get visitations if requested (This part was already correct)
        if ($type === 'visitation' || $type === 'both') {
            $query = Visitation::whereIn('child_id', $childrenIds)
                ->with(['child', 'parent.roles']);

            if ($request->child_id) {
                $query->where('child_id', $request->child_id);
            }
            if ($request->start_date) {
                $query->whereDate('date_start', '>=', $request->start_date);
            }
            if ($request->end_date) {
                $query->whereDate('date_start', '<=', $request->end_date);
            }
            if ($request->assigned_to) {
                $query->where('parent_id', $request->assigned_to);
            }
            $visitations = $query->get();
        }

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.calendar_pdf', compact('events', 'visitations', 'type'));
            return $pdf->download('calendar_report_' . now()->format('Ymd_His') . '.pdf');
        } else {
            return Excel::download(new CalendarReportExport($events, $visitations, $type), 'calendar_report_' . now()->format('Ymd_His') . '.csv');
        }
    }
}
