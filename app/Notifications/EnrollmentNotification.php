<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnrollmentNotification extends Notification
{
    use Queueable;

    private $payload;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
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
        return (new MailMessage)
                    ->subject('LZDS Enrollment')
                    ->greeting('Dear '.$this->payload['parent'].',')
                    ->line('Congratulations!')
                    ->line('Your enrollment has been successfully submitted. Details are as follows')
                    ->line('Student: '.$this->payload['student'])
                    ->line('Grade/Level: '.$this->payload['grade'])
                    ->line('Enrollment Reference Number: '.$this->payload['enrollee_rn'])
                    ->line('Payment Method: '.$this->payload['payment_method'])
                    ->line('Amount to pay: Php '.$this->payload['amount_to_pay'])
                    ->line('Please click on Payment Instruction for payment')
                    ->action('Payment Instruction', $this->payload['url'])
                    ->line('Thank you!');
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
