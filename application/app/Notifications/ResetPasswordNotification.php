<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    protected $token;
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        // definir os canais pelos quais a notificação deve ser enviada
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/reset-password-token?token=' . $this->token);

        return (new MailMessage)
            ->line('Você está recebendo este e-mail porque recebemos um pedido de redefinição de senha para sua conta.')
            ->action('Redefinir Senha', $url)
            ->line('Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.');
    }

}
