<div id="chat-container" class="d-flex justify-content-center align-items-center">
    <div id="chat-box" class="chat-box">
        <!-- Display the recipient's name at the top -->
        <h3 class="recipient-name">Discussion avec {{ $recipientName }}</h3>

        <!-- Messages container -->
        <div id="messages" class="messages-container">
            @if ($messages->isEmpty())
                <p>Aucun message à afficher.</p>
            @else
                @foreach ($messages as $message)
                    <div class="{{ $message->from_user_id === auth()->id() ? 'message-sent' : 'message-received' }}">
                        <p class="message-content">{{ $message->content }}</p>
                        <span class="timestamp">{{ $message->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Input and send button -->
        <div class="input-container">
            <input type="text" wire:model="message" placeholder="Écrivez votre message ici..." class="message-input">
            <button wire:click="sendMessage" class="send-button">Envoyer</button>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.1/dist/echo.iife.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: "94388195ca4fd69a0daf",
            cluster: "eu",
            encrypted: false,
        });

        // Listen for the MessageSent event and trigger message refresh
        Echo.channel('chat')
            .listen('MessageSent', (e) => {
                @this.call('refreshMessages').then(() => {
                    scrollToBottom(); // Scroll to the latest message after refresh
                });
            });

        // Function to scroll to the bottom of the messages container
        function scrollToBottom() {
            const messagesDiv = document.getElementById('messages');
            messagesDiv.scrollTop = messagesDiv.scrollHeight; // Scroll down
        }
    });
</script>
