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

    private $frequency;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($frequency)
    {
        $this->frequency = $frequency;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TODO
        if ($this->frequency == 'weekly') {
            $sales = ShoppingList::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->get();
            $totalSum = $this->getSumOfProductsPrice($sales);
            Mail::to(config('mail.from.address'))->send(new MailSalesReportWeekly($sales, $totalSum));
        } else {
            $sales = ShoppingList::whereMonth('created_at', '=', date('m'))->get();
            $totalSum = $this->getSumOfProductsPrice($sales);
            Mail::to(config('mail.from.address'))->send(new SalesReportMonthly($sales, $totalSum));
        }
    }
    private function getSumOfProductsPrice($sales) 
    {
        $total = 0;
        foreach ($sales as $item) { //todo
            $total += $item->product['price'];
        }
        return $total;
    }
}
