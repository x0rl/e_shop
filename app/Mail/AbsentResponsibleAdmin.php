<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbsentResponsibleAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $product;
    public $link;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $product, $link) //todo
    {
        $this->user = $user;
        $this->product = $product;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('tbhas@mail.ru', 'oh no')
            ->view('e_shop.mail.ResponsibleAdminNotIsset');
        //ResponsibleAdminNotIsset.blade
    }
}
