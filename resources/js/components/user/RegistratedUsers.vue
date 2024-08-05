<template>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container mt-5">
        <h2>Registered users</h2>
        <div v-if="registrations.length">
            <table class="table table-dark table-borderless">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Payment</th>
                    <th>Registered at</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="registration in registrations" :key="registration.id" @click="redirectToUserDetail(registration.user.id)" style="cursor: pointer;">
                    <td>{{ registration.user.name }}</td>
                    <td>{{ registration.user.email}}</td>
                    <td>{{ registration.payment_status }}</td>
                    <td>{{ registration.registration_date }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div v-else>
            <p>No registrations available.</p>
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
            eventId: null,
            registrations: [],
            errorMessage: ''
        };
    },
    mounted() {
        this.eventId = this.getEventIdFromUrl();
        if (this.eventId) {
            this.fetchEvents();
        } else {
            console.error('Event ID not found in URL');
        }
    },
    methods: {
        getEventIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            return pathParts[pathParts.length - 1];
        },
        fetchEvents() {
            axios
                .get(`/api/registrations/${this.eventId}`)
                .then(response => {
                    this.registrations = response.data;
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    this.errorMessage = 'Error fetching events.';
                });
        },
        redirectToUserDetail(userId) {
            window.location.href = `/user/${userId}`;
            console.log(userId);
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
