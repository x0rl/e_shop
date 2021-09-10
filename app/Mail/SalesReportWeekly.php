<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SalesReportWeekly extends Mailable
{
    use Queueable, SerializesModels;

    public $sales;
    public $total;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sales, $total)
    {
        $this->sales = $sales;
        $this->total = $total;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('e_shop.mail.sales-weekly-report');
    }
}
