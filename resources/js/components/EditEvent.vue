<template>
    <div class="container">
        <h1>Edit event</h1>
        <form @submit.prevent="updateEventData" v-if="event">
            <div class="form-group mb-3">
                <label for="title">Title</label>
                <input
                    type="text"
                    class="form-control"
                    id="title"
                    v-model="event.title"
                    required
                />
            </div>
            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea
                    class="form-control"
                    id="description"
                    v-model="event.description"
                    required
                >event.description</textarea>
            </div>
            <div class="form-group mb-3">
                <label for="event_date">Date</label>
                <input
                    type="date"
                    class="form-control"
                    id="event_date"
                    v-model="event.event_date"
                    required
                />
            </div>
            <div class="form-group mb-3">
                <label for="event_time">Time</label>
                <input
                    type="time"
                    class="form-control"
                    id="event_time"
                    v-model="event.event_time"
                    required
                />
            </div>
            <div class="form-group mb-3">
                <label for="location">Location</label>
                <input
                    type="text"
                    class="form-control"
                    id="location"
                    v-model="event.location"
                    required
                />
            </div>
            <div class="form-group mb-3">
                <label for="type">Type</label>
                <select class="form-control" id="type" v-model="event.type" required>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="price">Price</label>
                <input
                    type="number"
                    class="form-control"
                    id="price"
                    v-model="event.price"
                />
            </div>
            <div class="form-group mb-3">
                <label for="sport">Sport</label>
                <select class="form-control" id="sport" v-model="event.sport_id" required>
                    <option v-for="sport in sports" :key="sport.id" :value="sport.id">{{ sport.name }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Edit Event</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import {tryCatch} from "alpinejs/src/utils/error.js";

export default {
    data() {
        return {
            event: null,
            eventId: null,
            sports: []
        };
    },
    mounted() {
        this.eventId = this.getEventIdFromUrl();
        if (this.eventId) {
            this.fetchEventData();
        } else {
            console.error('Event ID not found in URL');
        }
    },
    async created() {
        try {
            const response = await axios.get('/api/sports');
            this.sports = response.data;
        } catch (error) {
            console.error('Error fetching sports:', error);
        }
    },
    methods: {
        getEventIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            console.log(pathParts[pathParts.length - 2]);
            return pathParts[pathParts.length - 2];
        },
        async fetchEventData() {
            try {
                const response = await axios.get(`/api/events/${this.eventId}`);
                this.event = response.data;
                console.log(response.data);
            } catch (error) {
                console.error('Error fetching event data:', error);
            }
        },
        async updateEventData(){
            try{
                if (this.event.event_time && this.event.event_time.length === 8) {
                    this.event.event_time = this.event.event_time.substring(0, 5);
                }

                await axios.put(`/api/events/${this.event.id}`, this.event)
                    .then(window.location.href = '/events/my-events');
            } catch (error) {
                console.error('Error updating event:', error);
            }
        }

    }
};
</script>
<style scoped>
.container {
    max-width: 600px;
    margin: auto;
}

h1 {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.alert {
    margin-top: 20px;
}
</style>
