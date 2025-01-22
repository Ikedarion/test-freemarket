<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchase;
    public $completedAt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($purchase, $completedAt)
    {
        $this->purchase = $purchase;
        $this->completedAt = $completedAt;
    }


    /**
     * Create a new message instance.
     *
     * @param  $purchase  購入情報
     * @return void
     */
    public function build()
    {
        return $this->view('emails.payment_success')
                    ->subject('決済完了のお知らせ')
                    ->with([
                        'purchase' => $this->purchase
                    ]);
    }
}
