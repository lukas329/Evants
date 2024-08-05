<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div v-if="event" class="card">
                    <div class="card-header">
                        <h2>{{ event.title }}</h2>
                    </div>
                    <div class="card-body">
                        <p><strong>Description:</strong> {{ event.description }}</p>
                        <p><strong>Date:</strong> {{ event.event_date }}</p>
                        <p><strong>Time:</strong> {{ event.event_time }}</p>
                        <p><strong>Location:</strong> {{ event.location }}</p>
                        <p><strong>Type:</strong> {{ event.type }}</p>
                        <p><strong>Price:</strong> {{ event.price }}</p>
                        <p><strong>Sport:</strong> {{ event.sport ? event.sport.name : 'N/A' }}</p>
                        <p><strong>Organizer:</strong> {{ event.organizer ? event.organizer.name : 'N/A' }}</p>
                    </div>
                </div>
                <div v-else class="text-center">
                    <p>Loading event details...</p>
                </div>
                <div class="card-body">
                    <button @click="leaveReview" class="btn btn-primary">Leave a review</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            event: null,
            eventId: null
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
    methods: {
        getEventIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            return pathParts[pathParts.length - 1];
        },
        async fetchEventData() {
            try {
                const response = await axios.get(`/api/events/${this.eventId}`);
                this.event = response.data;
            } catch (error) {
                console.error('Error fetching event data:', error);
            }
        },
        leaveReview() {
            location.href = `/event-review/${this.eventId}`;
        }
    }
};
</script>
