<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $url;

    /**
     * Create a new message instance.
     *
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        $this->url = env('app.url') . '/api/accept_invite/' . $uuid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Create your profile')
            ->markdown('emails.invite')
            ->with(['url' => $this->url]);
    }
}
