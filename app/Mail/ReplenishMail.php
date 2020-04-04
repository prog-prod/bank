<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReplenishMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $money;

    /**
     * ReplenishMail constructor.
     * @param string $subject
     * @param int $money
     */
    public function __construct(string $subject, int $money)
    {
        $this->subject = $subject;
        $this->money = $money;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.replenish', [ 'money' => $this->money ])
            ->from(config('mail.from.address'),config('mail.from.name'))
            ->cc(config('mail.from.address'),config('mail.from.name'))
            ->bcc(config('mail.from.address'),config('mail.from.name'))
            ->replyTo(config('mail.from.address'),config('mail.from.name'))
            ->subject($this->subject);
    }
}
