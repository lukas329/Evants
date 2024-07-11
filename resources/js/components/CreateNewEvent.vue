<template>
    <div class="container">
        <h1>Create New Event</h1>
        <form @submit.prevent="createEvent">
            <div class="form-group mb-3">
                <label for="title">Title</label>
                <input
                    type="text"
                    class="form-control"
                    id="title"
                    v-model="form.title"
                    required
                />
            </div>
            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea
                    class="form-control"
                    id="description"
                    v-model="form.description"
                    required
                ></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="event_date">Date</label>
                <input
                    type="date"
                    class="form-control"
                    id="event_date"
                    v-model="form.event_date"
                    required
                />
            </div>
            <div class="form-group mb-3">
                <label for="event_time">Time</label>
                <input
                    type="time"
                    class="form-control"
                    id="event_time"
                    v-model="form.event_time"
                    required
                />
            </div>
            <div class="form-group mb-3">
                <label for="location">Location</label>
                <input
                    type="text"
                    class="form-control"
                    id="location"
                    v-model="form.location"
                    required
                />
            </div>
            <div class="form-group mb-3">
                <label for="type">Type</label>
                <select class="form-control" id="type" v-model="form.type" required>
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
                    v-model="form.price"
                />
            </div>
            <div class="form-group mb-3">
                <label for="sport">Sport</label>
                <select class="form-control" id="sport" v-model="form.sport_id" required>
                    <option v-for="sport in sports" :key="sport.id" :value="sport.id">{{ sport.name }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Event</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                title: '',
                description: '',
                event_date: '',
                event_time: '',
                location: '',
                type: 'public',
                price: 0,
                sport_id: null
            },
            sports: []
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
        async createEvent() {
            try {
                await axios.post('/events', this.form);
                alert('Event created successfully');
                this.form = { title: '', description: '', event_date: '', event_time: '', location: '', type: 'public', price: 0, sport_id: null };
                window.location.href = '/events'; // Redirect to events index
            } catch (error) {
                console.error('Error creating event:', error);
                alert('Error creating event');
            }
        }
    }
}
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
