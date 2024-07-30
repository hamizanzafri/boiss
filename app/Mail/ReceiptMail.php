<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $imagePath;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @param string $imagePath
     * @return void
     */
    public function __construct(Order $order, $imagePath)
    {
        $this->order = $order;
        $this->imagePath = $imagePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.receipt')
                    ->attach($this->imagePath, [
                        'as' => 'receipt.png',
                        'mime' => 'image/png',
                    ]);
    }
}
