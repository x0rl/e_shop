<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NewDiscount as MailNewDiscount;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use App\Models\Favorites;
use App\Models\User;

class DiscountNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $product;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Отправляет письмо на почту всем, у кого данный товар в списке любимых
        foreach (Favorites::where('product_id', $this->product->id)->get() as $item) {
            Mail::to(User::find($item->user_id)->email)->send(new MailNewDiscount($this->product));
        }
    }
}
