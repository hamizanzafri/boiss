<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $ticketPath = Storage::disk('public')->path($this->ticket->ticket_path);

        return $this->view('emails.ticket')
                    ->subject('Your Event Ticket')
                    ->attach($ticketPath)
                    ->with([
                        'ticket' => $this->ticket,
                    ]);
    }
}