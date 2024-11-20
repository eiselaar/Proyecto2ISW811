import './bootstrap';
import Alpine from 'alpinejs';
import { createApp } from 'vue';
import ScheduleCalendar from './components/ScheduleCalendar.vue';
import PostEditor from './components/PostEditor.vue';

window.Alpine = Alpine;
Alpine.start();

// Inicializar Vue para componentes específicos
if (document.getElementById('schedule-calendar')) {
    createApp(ScheduleCalendar).mount('#schedule-calendar');
}

if (document.getElementById('post-editor')) {
    createApp(PostEditor).mount('#post-editor');
}

// Configuración de notificaciones en tiempo real
window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        // Mostrar notificación usando Toast
        Toast.fire({
            icon: notification.type,
            title: notification.message
        });
    });
