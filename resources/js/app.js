import './bootstrap'; // Si tienes un archivo bootstrap.js para Axios u otras configuraciones
import { createApp } from 'vue';
import WelcomeMessage from './components/WelcomeMessage.vue'; // Asegúrate de la ruta correcta

const app = createApp({});

// Registra tu componente Vue globalmente, o localmente en otro componente principal si lo deseas
app.component('welcome-message', WelcomeMessage);

app.mount('#app'); // Monta la aplicación Vue en un elemento con ID 'app'