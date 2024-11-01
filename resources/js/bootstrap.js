import 'bootstrap';

// Importation de la bibliothèque Echo
import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '94388195ca4fd69a0daf', // Clé Pusher
    cluster: 'eu', // Cluster Pusher
    encrypted: true
});
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: '94388195ca4fd69a0daf',
    cluster: 'eu',
    forceTLS: false,
});
/**
 * Nous allons charger la bibliothèque HTTP axios qui nous permet d'envoyer
 * facilement des requêtes à notre back-end Laravel. Cette bibliothèque
 * gère automatiquement l'envoi du token CSRF en tant qu'en-tête basé sur
 * la valeur du cookie "XSRF".
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
