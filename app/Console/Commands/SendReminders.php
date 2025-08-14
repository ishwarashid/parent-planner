<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visitation;
use App\Models\Expense;
use App\Models\User;
use App\Notifications\VisitationReminderNotification;
use App\Notifications\ExpenseReminderNotification;
use Carbon\Carbon;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send upcoming visitation and pending expense reminders.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending reminders...');

        // Send visitation reminders (e.g., for visitations starting in the next 24 hours)
        $upcomingVisitations = Visitation::where('date_start', '>', Carbon::now())
                                        ->where('date_start', '<=', Carbon::now()->addDay())
                                        ->get();

        foreach ($upcomingVisitations as $visitation) {
            $visitation->parent->notify(new VisitationReminderNotification($visitation));
            $this->info('Sent visitation reminder for ' . $visitation->child->name . ' to ' . $visitation->parent->name);
        }

        // Send expense reminders (e.g., for pending expenses older than 7 days)
        $pendingExpenses = Expense::where('status', 'pending')
                                ->where('created_at', '<=', Carbon::now()->subDays(7))
                                ->get();

        foreach ($pendingExpenses as $expense) {
            $expense->payer->notify(new ExpenseReminderNotification($expense));
            $this->info('Sent expense reminder for ' . $expense->description . ' to ' . $expense->payer->name);
        }

        $this->info('Reminders sent successfully!');
    }
}

