<?php

namespace App\Notifications;

use App\Mail\LoginMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $loginDevice;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $loginDevice)
    {
        $this->user = $user;
        $this->loginDevice = $loginDevice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new LoginMail($this->user, $this->loginDevice));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'created' => $this->user,
            'receiver' => $notifiable,
            'loginDevice' => $this->loginDevice,
            'action' => 'Login'
        ];
    }
}
