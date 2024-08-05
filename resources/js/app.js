import './bootstrap';
import { createApp } from 'vue';
import Alpine from 'alpinejs';
import axios from 'axios';
import EventsByMe from "./components/event/EventsByMe.vue";
import EventsDetail from "./components/event/EventsDetail.vue";
import CreateNewEvent from "./components/event/CreateNewEvent.vue";
import EventsIndex from "./components/event/EventsIndex.vue";
import EditEvent from "./components/event/EditEvent.vue";
import RegistratedUsers from "./components/user/RegistratedUsers.vue";
import UserDetail from "./components/user/UserDetail.vue";
import ChatMessages from "./components/chat/ChatMessages.vue";
import ChatForm from "./components/chat/ChatForm.vue";
import Dashboard from "./components/Dashboard.vue";
import UserReview from "./components/user/UserReview.vue";
import EventReview from "./components/event/EventReview.vue";
import ProfileEdit from "./components/profile/ProfileEdit.vue";

axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const app = createApp({
    data() {
        return {
            messages: [] // Store chat messages for display in this array
        };
    },
    methods: {
        addMessage(message) {
            // Pushes it to the messages array
            this.messages.push(message);
            // POST request to the messages route with the message data in order for our Laravel server to broadcast it.
            axios.post('/send-message', message).then(response => {
                console.log(response.data);
            });
        }
    }
});

// Register components globally
app.component('events-index', EventsIndex);
app.component('create-event', CreateNewEvent);
app.component('events-detail', EventsDetail);
app.component('events-by-me', EventsByMe);
app.component('edit-event', EditEvent);
app.component('regis-users', RegistratedUsers);
app.component('user-detail', UserDetail);
app.component('chat-messages', ChatMessages);
app.component('chat-form', ChatForm);
app.component('dashboard', Dashboard);
app.component('user-review', UserReview);
app.component('event-review', EventReview);
app.component('profile-edit', ProfileEdit)

app.mount('#app');

window.Alpine = Alpine;
Alpine.start();
