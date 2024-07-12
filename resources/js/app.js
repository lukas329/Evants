import './bootstrap';
import { createApp } from 'vue';
import Alpine from 'alpinejs';
import EventsByMe from "./components/EventsByMe.vue";
import EventsDetail from "./components/EventsDetail.vue";
import CreateNewEvent from "./components/CreateNewEvent.vue";
import EventsIndex from "./components/EventsIndex.vue";
import axios from 'axios';
import EditEvent from "./components/EditEvent.vue";
import RegistratedUsers from "./components/RegistratedUsers.vue";
import UserDetail from "./components/UserDetail.vue";

axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const app = createApp({
    components: {
        'events-index': EventsIndex,
        'create-event': CreateNewEvent,
        'events-detail': EventsDetail,
        'events-by-me':EventsByMe,
        'edit-event': EditEvent,
        'regis-users': RegistratedUsers,
        'user-detail':UserDetail
    },
});
app.mount('#app');

window.Alpine = Alpine;

Alpine.start();
