<?php

namespace App\Jobs;

use App\Mail\SalesReportMonthly;
use App\Mail\SalesReportWeekly as MailSalesReportWeekly;
use App\Models\ShoppingList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SalesReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $fast;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fast)
    {
        if ($fast == 'weekly') {
            $this->fast = 'weekly';
        } else {
            $this->fast = 'monthly';
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TODO
        if ($this->fast == 'weekly') {
            $sales = ShoppingList::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->get();
            $total = 0;
            foreach ($sales as $item) { //todo
                $total += $item->product['price'];
            }
            Mail::to(env('ADMIN_EMAIL'))->send(new MailSalesReportWeekly($sales, $total));
        } else {
            $sales = ShoppingList::whereMonth('created_at', '=', date('m'))->get();
            $total = 0;
            foreach ($sales as $item) { //todo
                $total += $item->product['price'];
            }
            Mail::to(env('ADMIN_EMAIL'))->send(new SalesReportMonthly($sales, $total));
        }
    }
}
