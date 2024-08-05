<template>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container mt-5">
        <h2>Events</h2>
        <div class="filters mb-4">
            <div class="form-group">
                <label for="search">Search</label>
                <input type="text" id="search" v-model="filters.searchQuery" placeholder="Search..." class="form-control">
            </div>
            <div class="form-group">
                <label for="joined">Show Joined Events</label>
                <input type="checkbox" id="joined" v-model="filters.joined" class="form-check-input">
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" v-model="filters.start_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" v-model="filters.end_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="sport">Sport</label>
                <select id="sport" v-model="filters.sport_id" class="form-control">
                    <option value="">All</option>
                    <option v-for="sport in sports" :key="sport.id" :value="sport.id">{{ sport.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" v-model="filters.location" class="form-control">
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" v-model="filters.type" class="form-control">
                    <option value="">All</option>
                    <option value="private">Private</option>
                    <option value="public">Public</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-filter" @click="applyFilters">Apply Filters</button>
            </div>
        </div>
        <div v-if="filteredEvents.length">
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
                <tr v-for="event in filteredEvents" :key="event.id" @click="redirectToDetail(event.id)" style="cursor: pointer;">
                    <td>{{ event.title }}</td>
                    <td>{{ event.sport ? event.sport.name : 'None' }}</td>
                    <td>{{ event.event_date }}</td>
                    <td>{{ event.event_time }}</td>
                    <td>{{ event.location }}</td>
                    <td>{{ event.type }}</td>
                    <td>${{ event.price }}</td>
                    <td>
                        <button
                            :class="['btn', event.has_joined ? 'btn-danger' : 'btn-primary']"
                            @click.stop="toggleEventRegistration(event.id, event.has_joined)"
                        >
                            {{ event.has_joined ? 'Cancel' : 'Join' }}
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
            sports: [], // Assuming you'll fetch these from the server
            errorMessage: '',
            filters: {
                searchQuery: '',
                joined: false,
                start_date: '',
                end_date: '',
                organizer_id: '',
                sport_id: '',
                location: '',
                type: ''
            }
        };
    },
    created() {
        this.fetchEvents();
        this.fetchSports();
    },
    computed: {
        filteredEvents() {
            return this.events.filter(event => {
                let matchesSearchQuery = true;
                if (this.filters.searchQuery) {
                    const query = this.filters.searchQuery.toLowerCase();
                    matchesSearchQuery = event.title.toLowerCase().includes(query) ||
                        (event.organizer && event.organizer.name.toLowerCase().includes(query)) ||
                        event.location.toLowerCase().includes(query) ||
                        (event.sport && event.sport.name.toLowerCase().includes(query));
                }

                const matchesJoined = !this.filters.joined || event.has_joined;
                const matchesStartDate = !this.filters.start_date || event.event_date >= this.filters.start_date;
                const matchesEndDate = !this.filters.end_date || event.event_date <= this.filters.end_date;
                const matchesSport = !this.filters.sport_id || event.sport_id == this.filters.sport_id;
                const matchesLocation = !this.filters.location || event.location.toLowerCase().includes(this.filters.location.toLowerCase());
                const matchesType = !this.filters.type || event.type == this.filters.type;

                return matchesSearchQuery && matchesJoined && matchesStartDate && matchesEndDate && matchesSport && matchesLocation && matchesType;
            });
        }
    },
    methods: {
        toggleEventRegistration(eventId, hasJoined) {
            if (hasJoined) {
                this.cancelEvent(eventId);
            } else {
                this.joinEvent(eventId);
            }
        },
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
        fetchSports() {
            axios
                .get('/api/sports')
                .then(response => {
                    this.sports = response.data;
                })
                .catch(error => {
                    console.error('Error fetching sports:', error);
                });
        },
        joinEvent(eventId) {
            axios
                .post(`api/events/${eventId}/join`, {}, {
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
        async cancelEvent(eventId) {
            try {
                await axios.post(`/api/events/${eventId}/cancel`);
                this.updateEventStatus(eventId, false);
            } catch (error) {
                console.error('Error cancelling event:', error);
            }
        },
        updateEventStatus(eventId, hasJoined) {
            const event = this.events.find(event => event.id === eventId);
            if (event) {
                event.has_joined = hasJoined;
            }
        },
        redirectToDetail(eventId) {
            window.location.href = `/events/${eventId}`;
            console.log(eventId);
        },
        applyFilters() {
            this.fetchEvents();
        }
    }
};
</script>




<style scoped>
.filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

.form-group {
    flex: 1;
    min-width: 150px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    width: 100px;
    height: 40px;
    cursor: pointer;
    text-align: center;
    display: inline-block;
    line-height: 20px;
}
.btn-filter{
    width: 200px;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-danger {
    background-color: #dc3545;
    color: #fff;
}

.btn-danger:hover {
    background-color: #c82333;
}

.container {
    max-width: 1200px;
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
