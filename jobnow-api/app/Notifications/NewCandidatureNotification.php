<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCandidatureNotification extends Notification
{
    use Queueable;

    public $candidature;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\Candidature $candidature)
    {
        $this->candidature = $candidature;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $candidatName = 'Candidat';
        
        if ($this->candidature->candidat) {
            $nom = $this->candidature->candidat->nom ?? 'N/A';
            $prenom = $this->candidature->candidat->prenom ?? 'N/A';
            $candidatName = trim($nom . ' ' . $prenom);
        }

        return [
            'candidat_name' => $candidatName,
            'offre_title' => $this->candidature->offre->titre ?? 'Offre',
            'candidature_id' => $this->candidature->id,
            'message' => 'Nouvelle candidature reçue',
        ];
    }
}
