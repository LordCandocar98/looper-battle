<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RewardCoinNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $coinAmount;
    protected $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($coinAmount, $user)
    {
        $this->coinAmount = $coinAmount;
        $this->user = $user;
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
        // return (new MailMessage)
        //     ->subject('¡Has recibido una nueva recompensa!')
        //     ->line('¡Felicidades! Has recibido una recompensa de ' . $this->coinAmount . ' looper coins.')
        //     ->line('Podrás visualizar tu recompensa en el juego.');

        return (new MailMessage)
            ->subject('¡Has recibido una nueva recompensa!')
            ->greeting('¡Hola, ' . $this->user->nickname . '!')
            ->line('¡Felicidades! Has recibido una recompensa de ' . $this->coinAmount . ' looper coins.')
            ->line('Podrás visualizar tu recompensa en el juego.')
            ->salutation('¡Saludos, Atentamente: el Equipo de ' . config('app.name'))
            ->markdown('vendor.notifications.email', [
                'banner' => url('storage/' . setting('admin.bannerlooper'))
            ]);
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
