<?php
namespace App\Livewire;

use Livewire\Component;
use App\Events\MessageSent;
use App\Models\Message; // Assurez-vous d'avoir un modèle Message
use App\Models\User;
class Chat extends Component
{
    protected $listeners = ['MessageSent' => 'refreshMessages'];

    public $message;
    public $messages = []; // Initialisez comme un tableau vide
    public $userId;
    public $recipientName;
    // La méthode mount pour récupérer l'ID de l'utilisateur
    public function mount($userId)
    {
        $this->userId = $userId;
        $this->recipientName = User::find($this->userId)->name ?? 'Utilisateur inconnu';
        // Charger les messages ici
        $this->refreshMessages();
    }
    public function getListeners()
    {
        return [
            'echo:chat,MessageSent' => 'refreshMessages',
        ];
    }

    public function refreshMessages()
    {

        // Charger les messages ici
        $this->messages = Message::where(function ($query) {
            $query->where('from_user_id', $this->userId)
                ->where('to_user_id', auth()->id());
        })
            ->orWhere(function ($query) {
                $query->where('from_user_id', auth()->id())
                    ->where('to_user_id', $this->userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
//        dd($this->messages);
    }

    public function sendMessage()
    {
        // Émet l'événement avec le message saisi
        broadcast(new MessageSent($this->message))->toOthers();

        // Sauvegarde le message dans la base de données si nécessaire
        Message::create(['content' => $this->message, 'from_user_id' => auth()->user()->id, 'to_user_id' => $this->userId]);

        // Réinitialise le message après l’envoi
        $this->message = '';

        // Recharge les messages (optionnel, selon ta logique)
        $this->refreshMessages();
    }

    public function render()
    {   logger("render");
        return view('livewire.chat'); // Modifie le nom du fichier de vue

    }
}
