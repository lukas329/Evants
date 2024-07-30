<template>
    <div class="stats-container">
        <div class="card">
            <h3>Number of Events Attended</h3>
            <p>{{ stats.attendedEventsCount }}</p>
        </div>
        <div class="card">
            <h3>Number of Registered Events</h3>
            <p>{{ stats.registeredEventsCount }}</p>
        </div>
        <div class="card">
            <h3>Reviews</h3>
            <p>{{ reviewData.reviewCount }}</p>
        </div>
        <div class="card">
            <h3>Rating</h3>
            <p>{{ reviewData.rating }}</p>
        </div>
        <div class="card" v-if="stats.nextEvent">
            <h3>Next Event</h3>
            <p>Title: {{ stats.nextEvent.title }}</p>
            <p>Date: {{ stats.nextEvent.event_date }}</p>
            <p>Time: {{ stats.nextEvent.event_time }}</p>
            <p>Location: {{ stats.nextEvent.location }}</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            stats: {
                attendedEventsCount: 0,
                registeredEventsCount: 0,
                nextEvent:null,
            },
            reviewData:{
                reviewCount: 0,
                rating: 0
            }
        };
    },
    mounted() {
        this.fetchUserStats();
        this.fetchReviewCount();
    },
    methods: {
        async fetchUserStats() {
            try {
                const response = await axios.get('/api/stats');
                this.stats = response.data;
                console.log(response.data);
            } catch (error) {
                console.error('Error fetching user stats:', error);
            }
        },
        async fetchReviewCount(){
            try{
                const response = await axios.get('/api/reviewData');
                this.reviewData = response.data;
            }catch (error) {
                console.error('Error fetching review stats:', error);
            }
        }
    }
};
</script>
<style scoped>
.stats-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
    justify-content: space-around;
}

.card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
    flex: 1 1 calc(50% - 40px); /* 50% width minus the gap */
    box-sizing: border-box;
    max-width: calc(50% - 40px); /* Ensure the cards do not grow larger than 50% of the container */
}

.card h3 {
    margin-top: 0;
}

.card p {
    margin: 10px 0;
}
</style>

