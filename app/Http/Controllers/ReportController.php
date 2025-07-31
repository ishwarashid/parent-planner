<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Visitation;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VisitationsExport;
use App\Exports\ExpensesExport;

class ReportController extends Controller
{
    public function index()
    {
        $children = auth()->user()->children;
        return view('reports.index', compact('children'));
    }

    public function generateVisitationReport(Request $request)
    {
        $request->validate([
            'child_id' => 'nullable|exists:children,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,csv',
        ]);

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

        $visitations = $visitations->get();

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.visitations_pdf', compact('visitations'));
            return $pdf->download('visitation_report_' . now()->format('Ymd_His') . '.pdf');
        } else {
            return Excel::download(new VisitationsExport($visitations), 'visitation_report_' . now()->format('Ymd_His') . '.csv');
        }
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

        $expenses = auth()->user()->expenses();

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
}

