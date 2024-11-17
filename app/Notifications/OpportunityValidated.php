<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OpportunityValidated extends Notification
{
    use Queueable;

    protected $opportunity;

    public function __construct($opportunity)
    {
        $this->opportunity = $opportunity;
    }

    public function via($notifiable)
    {
        return ['database']; // La notification sera stockée dans la base de données
    }

    public function toDatabase($notifiable)
    {
        // Notification pour l'opportunité validée ou non validée

        if ($this->opportunity->etat === 'valide') {
            return [
                'message' => 'L\'opportunité avec le client ' . $this->opportunity->client . ' a été validée.',
                'opportunity_id' => $this->opportunity->id,
            ];
        } else {
            return [
                'message' => 'L\'opportunité avec le client ' . $this->opportunity->client . ' a été non validée.',
                'opportunity_id' => $this->opportunity->id,
            ];
        }
    }
}
