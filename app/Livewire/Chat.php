<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;

class Chat extends Component
{
    // Listeners to handle events
    protected $listeners = ['MessageSent' => 'refreshMessages'];

    // Public properties
    public $message;
    public $messages = [];
    public $userId;
    public $recipientName;

    /**
     * Mount method to initialize the component with user data.
     *
     * @param  int  $userId
     * @return void
     */
    public function mount($userId)
    {
        $this->userId = $userId;
        $this->recipientName = User::find($this->userId)->name ?? 'Utilisateur inconnu';

        // Load messages when the component is mounted
        $this->refreshMessages();
    }

    /**
     * Get listeners for the component.
     *
     * @return array
     */
    public function getListeners()
    {
        return [
            'echo:chat,MessageSent' => 'refreshMessages',
        ];
    }

    /**
     * Refresh the messages based on the userId and the authenticated user.
     *
     * @return void
     */
    public function refreshMessages()
    {
        // Fetch messages exchanged between the authenticated user and the recipient
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
    }

    /**
     * Send the message, broadcast it, and save it in the database.
     *
     * @return void
     */
    public function sendMessage()
    {
        // Broadcast the event with the message content
        broadcast(new MessageSent($this->message))->toOthers();

        // Save the message in the database
        Message::create([
            'content' => $this->message,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => $this->userId
        ]);

        // Reset the message input field after sending
        $this->message = '';

        // Optionally reload the messages after sending
        $this->refreshMessages();
    }

    /**
     * Render the chat view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.chat');
    }
}
