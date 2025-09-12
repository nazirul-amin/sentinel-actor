<?php

namespace NazirulAmin\SentinelActor\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NazirulAmin\SentinelActor\Traits\MonitorsExceptions;

class MonitoringExampleNotification extends Notification implements ShouldQueue
{
    use MonitorsExceptions, Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
        // Your notification logic here
        // If an exception is thrown, the failed() method in MonitorsExceptions will be called automatically
        throw new \Exception('Example notification exception for monitoring');
    }

    /**
     * Get monitoring context data.
     */
    protected function getMonitoringContextData(): array
    {
        return [
            'data' => $this->data,
        ];
    }
}
