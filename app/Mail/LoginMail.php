<?php

namespace App\Mail;

use App\Models\User;
use App\Services\AppMessages;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $loginDevice;
    public function __construct(User $user, $loginDevice)
    {
        $this->user = $user;
        $this->loginDevice = $loginDevice;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject:  AppMessages::NEW_LOGIN_ALERT,
            to: $this->user->email,
            from: new Address(config('game.from_address'), config('game.from_name')),

        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.login',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            // Attachment::fromPath('tesla payment.pdf')
            //     ->as(' Invoice Request Fulfillment.pdf')
            //     ->withMime('application/pdf'),
        ];
    }
}
