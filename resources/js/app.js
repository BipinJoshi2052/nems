import './bootstrap';
import { createApp } from 'vue';

const el = document.getElementById('app');
if (el) {
    createApp({
        name: 'NepalEms',
        template: '<div class="container py-4"><p class="text-muted mb-0">Vue 3 + Bootstrap 5 (foundation)</p></div>',
    }).mount(el);
}
