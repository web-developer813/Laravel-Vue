<?php

namespace App\Notifications\Forprofits;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Donation;
use App\Mail\Forprofits\DonationRequest as DonationRequestMail;
use Mail;

class DonationRequest extends Notification
{
    use Queueable;

    protected $donation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return new DonationRequestMail($this->donation, $notifiable);
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
            //
        ];
    }
}
