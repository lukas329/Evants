<template>
    <div class="container mt-5">
        <h2>My Events</h2>
        <div v-if="events.length">
            <table class="table">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Sport</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="event in events"
                    :key="event.event_id"
                    @click="redirectToDetail(event.id)"
                    style="cursor: pointer;"
                >
                    <td>{{ event.title }}</td>
                    <td>{{ event.description }}</td>
                    <td>{{ event.event_date }}</td>
                    <td>{{ event.event_time }}</td>
                    <td>{{ event.location }}</td>
                    <td>{{ event.type }}</td>
                    <td>${{ event.price }}</td>
                    <td>{{ event.sport ? event.sport.name : 'N/A' }}</td>
                    <td>
                        <button
                            class="btn btn-primary"
                            @click.stop="editEvent(event.id)"
                        >
                            Edit
                        </button>
                        <button
                            class="btn btn-danger"
                            @click.stop="deleteEvent(event.id)"
                        >
                            Delete
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

export default {
    data() {
        return {
            events: [],
            errorMessage: ''
        };
    },
    created() {
        this.fetchMyEvents();
    },
    methods: {
        fetchMyEvents() {
            axios
                .get('/api/my-events')
                .then(response => {
                    this.events = response.data;
                })
                .catch(error => {
                    console.error('Error fetching event data:', error);
                    this.errorMessage = 'Error fetching events.';
                });
        },
        redirectToDetail(eventId) {
            window.location.href = `/events/${eventId}`;
        },
        editEvent(eventId) {
            // Redirect to the event edit page
            window.location.href = `/events/${eventId}/edit`;
        },
        deleteEvent(eventId) {
            axios
                .delete(`/api/events/${eventId}`)
                .then(response =>{
                    console.log(response)
                }).then(
                window.location.href = '/events/my-events'
            )
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
