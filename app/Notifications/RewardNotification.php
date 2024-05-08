<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RewardNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $item;
    protected $rewardCode;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($rewardCode, $user, $item)
    {
        $this->user = $user;
        $this->item = $item;
        $this->rewardCode = $rewardCode;
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
            ->subject('¡Has recibido un código de recompensa!')
            ->greeting('¡Hola, ' . $this->user->nickname . '!')
            ->line('¡Felicidades! El código es: ' . $this->rewardCode)
            ->line('Podrás canjearlo dentro del juego, para reclamar tu artículo (' . $this->item->name . ').')
            ->salutation('¡Saludos, Atentamente: el Equipo de ' . config('app.name'))
            ->markdown('vendor.notifications.email', [
                'banner' => url('storage/' . $this->item->image)
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
