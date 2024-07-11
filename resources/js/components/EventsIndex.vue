<template>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container mt-5">
        <h2>Events</h2>
        <div v-if="events.length">
            <table class="table">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Sport</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="event in events" :key="event.event_id" @click="redirectToDetail(event.id)" style="cursor: pointer;">
                    <td>{{ event.title }}</td>
                    <td>{{ event.sport ?  event.sport.name : 'None'}}</td>
                    <td>{{ event.event_date }}</td>
                    <td>{{ event.event_time }}</td>
                    <td>{{ event.location }}</td>
                    <td>{{ event.type }}</td>
                    <td>${{ event.price }}</td>
                    <td>
                        <button
                            class="btn btn-primary"
                            @click.stop="joinEvent(event.id)"
                            :disabled="event.has_joined"
                        >
                            Join
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div v-else>
            <p>No events available.</p>
        </div>
        <div v-if="errorMessage" class="alert alert-danger mt-3">{{ errorMessage }}</div>
    </div>
</template>

<script>
import axios from 'axios';
axios.defaults.withCredentials = true;
export default {
    data() {
        return {
            events: [],
            errorMessage: ''
        };
    },
    created() {
        this.fetchEvents();
    },
    methods: {
        fetchEvents() {
            axios
                .get('/api/events')
                .then(response => {
                    this.events = response.data;
                    console.log(this.events);
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    this.errorMessage = 'Error fetching events.';
                });
        },
        joinEvent(eventId) {
            axios
                .post(`/events/${eventId}/join`, {}, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    this.fetchEvents();
                })
                .catch(error => {
                    console.error('Error joining event:', error);
                    if (error.response && error.response.data && error.response.data.error) {
                        this.errorMessage = error.response.data.error;
                    } else {
                        this.errorMessage = 'An error occurred while joining the event.';
                    }
                });
        },
        redirectToDetail(eventId) {
            window.location.href = `/events/${eventId}`;
            console.log(eventId);
        }
    },
};
</script>

<style scoped>
.container {
    max-width: 800px;
    margin: auto;
}

h2 {
    margin-bottom: 20px;
}

.table {
    margin-bottom: 20px;
}

.alert {
    margin-top: 20px;
}

.table tr {
    transition: background-color 0.2s ease;
}

.table tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}
</style>
