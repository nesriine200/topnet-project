{{--<div wire:poll.1s="refreshMessages" >--}}
{{--<div  id="test">--}}
{{--    <div >--}}
{{--        @if($messages->isEmpty())--}}
{{--            <p>Aucun message à afficher.</p>--}}
{{--        @else--}}
{{--            @foreach($messages as $message)--}}
{{--                <div class="{{ $message->from_user_id === auth()->id() ? 'message-sent' : 'message-received' }}">--}}
{{--                    <p>{{ $message->content }}</p>--}}
{{--                    <span>{{ $message->created_at->diffForHumans() }}</span>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--        @endif--}}
{{--    </div>--}}

{{--    <input type="text" wire:model="message" placeholder="Écrivez votre message ici...">--}}
{{--    <button wire:click="sendMessage">Envoyer</button>--}}
{{--    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.1/dist/echo.iife.js"></script>--}}

{{--    <!-- Initialize Echo directly -->--}}
{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', function () {--}}
{{--            window.Echo = new Echo({--}}
{{--                broadcaster: 'pusher',--}}
{{--                key: "94388195ca4fd69a0daf",--}}
{{--                cluster: "eu",--}}
{{--                encrypted: false,--}}
{{--            });--}}

{{--            // Listen for the event and trigger Livewire component update--}}
{{--            Echo.channel('chat')--}}
{{--                .listen('MessageSent', (e) => {--}}
{{--                @this.call('refreshMessages');--}}
{{--                });--}}
{{--        });--}}
{{--    </script>--}}

{{--                </div>--}}
{{--////////--}}
{{--<div id="test">--}}
{{--    <div>--}}
{{--        @if($messages->isEmpty())--}}
{{--            <p>Aucun message à afficher.</p>--}}
{{--        @else--}}
{{--            @foreach($messages as $message)--}}
{{--                <div class="{{ $message->from_user_id === auth()->id() ? 'message-sent' : 'message-received' }}">--}}
{{--                    <p class="message-content">{{ $message->content }}</p>--}}
{{--                    <span class="timestamp">{{ $message->created_at->diffForHumans() }}</span>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--        @endif--}}
{{--    </div>--}}

{{--    <input type="text" wire:model="message" placeholder="Écrivez votre message ici..." style="padding: 8px; border-radius: 20px; width: calc(100% - 100px); margin-right: 10px;">--}}
{{--    <button wire:click="sendMessage" style="padding: 8px 15px; border-radius: 20px; background-color: #4CAF50; color: white; border: none;">Envoyer</button>--}}

{{--    <style>--}}
{{--        /* Conteneur du message envoyé */--}}
{{--        .message-sent {--}}
{{--            text-align: right;--}}
{{--            margin: 10px 0;--}}
{{--        }--}}

{{--        .message-received {--}}
{{--            text-align: left;--}}
{{--            margin: 10px 0;--}}
{{--        }--}}

{{--        /* Style de contenu pour les messages envoyés */--}}
{{--        .message-sent .message-content {--}}
{{--            background-color: #DCF8C6; /* Vert clair */--}}
{{--            color: #333;--}}
{{--            padding: 8px 15px;--}}
{{--            border-radius: 20px 20px 0 20px;--}}
{{--            display: inline-block;--}}
{{--            max-width: 70%;--}}
{{--            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);--}}
{{--        }--}}

{{--        /* Style de contenu pour les messages reçus */--}}
{{--        .message-received .message-content {--}}
{{--            background-color: #ECECEC; /* Gris clair */--}}
{{--            color: #333;--}}
{{--            padding: 8px 15px;--}}
{{--            border-radius: 20px 20px 20px 0;--}}
{{--            display: inline-block;--}}
{{--            max-width: 70%;--}}
{{--            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);--}}
{{--        }--}}

{{--        /* Style des horodatages */--}}
{{--        .timestamp {--}}
{{--            display: block;--}}
{{--            font-size: 12px;--}}
{{--            color: #999;--}}
{{--            margin-top: 5px;--}}
{{--        }--}}
{{--    </style>--}}

{{--    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.1/dist/echo.iife.js"></script>--}}

{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', function () {--}}
{{--            window.Echo = new Echo({--}}
{{--                broadcaster: 'pusher',--}}
{{--                key: "94388195ca4fd69a0daf",--}}
{{--                cluster: "eu",--}}
{{--                encrypted: false,--}}
{{--            });--}}

{{--            // Écouter l'événement et déclencher la mise à jour du composant Livewire--}}
{{--            Echo.channel('chat')--}}
{{--                .listen('MessageSent', (e) => {--}}
{{--                @this.call('refreshMessages');--}}
{{--                });--}}
{{--        });--}}
{{--    </script>--}}
{{--</div>--}}
{{--//////////--}}



<div id="chat-container" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div id="test" style="border: 2px solid #007BFF; border-radius: 10px; padding: 20px; width: 80%; background-color: #f9f9f9; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <!-- Affiche le nom de l'utilisateur cible en haut en bleu -->
        <h3 style="text-align: center; margin-bottom: 10px; color: #007BFF;">Discussion avec {{ $recipientName }}</h3>

        <div id="messages" style="max-height: 400px; overflow-y: auto; margin-bottom: 10px;">
            @if($messages->isEmpty())
                <p>Aucun message à afficher.</p>
            @else
                @foreach($messages as $message)
                    <div class="{{ $message->from_user_id === auth()->id() ? 'message-sent' : 'message-received' }}">
                        <p class="message-content">{{ $message->content }}</p>
                        <span class="timestamp">{{ $message->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            @endif
        </div>

        <div style="display: flex; margin-top: 10px;">
            <input type="text" wire:model="message" placeholder="Écrivez votre message ici..." style="padding: 8px; border-radius: 20px; width: calc(100% - 100px); margin-right: 10px;">
            <button wire:click="sendMessage" style="padding: 8px 15px; border-radius: 20px; background-color: #007BFF; color: white; border: none;">Envoyer</button>
        </div>
    </div>

    <style>
        /* Conteneur du message envoyé */
        .message-sent {
            text-align: right;
            margin: 10px 0;
        }

        .message-received {
            text-align: left;
            margin: 10px 0;
        }

        /* Style de contenu pour les messages envoyés */
        .message-sent .message-content {
            background-color: #ADD8E6; /* Bleu clair */
            color: #333;
            padding: 8px 15px;
            border-radius: 20px 20px 0 20px;
            display: inline-block;
            max-width: 70%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Style de contenu pour les messages reçus */
        .message-received .message-content {
            background-color: #ECECEC; /* Gris clair */
            color: #333;
            padding: 8px 15px;
            border-radius: 20px 20px 20px 0;
            display: inline-block;
            max-width: 70%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Style des horodatages */
        .timestamp {
            display: block;
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
    </style>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.1/dist/echo.iife.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: "94388195ca4fd69a0daf",
                cluster: "eu",
                encrypted: false,
            });

            // Écouter l'événement et déclencher la mise à jour du composant Livewire
            Echo.channel('chat')
                .listen('MessageSent', (e) => {
                @this.call('refreshMessages').then(() => {
                    scrollToBottom(); // Appelle le défilement après le rafraîchissement
                });
                });

            function scrollToBottom() {
                const messagesDiv = document.getElementById('messages');
                messagesDiv.scrollTop = messagesDiv.scrollHeight; // Défiler vers le bas
            }
        });
    </script>

</div>

