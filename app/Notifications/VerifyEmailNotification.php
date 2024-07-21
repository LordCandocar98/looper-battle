<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    protected $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($user)
    {
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
        $verificationUrl = url("/verify/{$this->user->id}");

        return (new MailMessage)
        ->subject('Verificación de Correo Electrónico')
        ->greeting('¡Hola, ' . $this->user->nickname . '!')
        ->line('Gracias por registrarte en nuestra aplicación. Estamos emocionados de tenerte a bordo.')
        ->line('Por favor, haz clic en el siguiente enlace para verificar tu correo electrónico.')
        ->action('Verificar Correo Electrónico', $verificationUrl)
        ->line('Una vez que verifiques tu correo, podrás disfrutar plenamente de todas las funciones de nuestra aplicación.')
        ->line('¡Diviértete jugando y gracias por formar parte de nuestra comunidad!')
        ->salutation('¡Saludos, Atentamente: el Equipo de ' . config('app.name'))
        ->markdown('vendor.notifications.email', [
            'actionUrl' => $verificationUrl,
            'actionText' => 'Verificar Correo Electrónico',
            'banner' => url('storage/' . setting('admin.bannerlooper')),
            'alternativeText' => 'Si tienes problemas al hacer clic en el botón de verificación, copia y pega la siguiente URL en tu navegador: ' . $verificationUrl,
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
