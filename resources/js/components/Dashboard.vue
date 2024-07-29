<template>
    <div class="stats-container">
        <div class="card" v-for="(stat, index) in statsList" :key="index">
            <h3>{{ stat.title }}</h3>
            <p v-if="stat.value">{{ stat.value }}</p>
            <div v-if="stat.event">
                <p>{{ stat.event.name }}</p>
                <p>{{ stat.event.date }}</p>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";

export default {
    data() {
        return {
            stats: {
                attendedEvents: 0,
                kilometersRan: 0,
                createdEvents: 0,
                rating: 0
            },
            nextEvent: {
                name: '',
                date: ''
            }
        };
    },
    computed: {
        statsList() {
            return [
                { title: 'Number of Events Attended', value: this.stats.attendedEvents },
                { title: 'Kilometers Ran with Events', value: `${this.stats.kilometersRan} km` },
                { title: 'Next Event Attended', event: this.nextEvent },
                { title: 'Number of Events Created', value: this.stats.createdEvents },
                { title: 'Rating', value: this.stats.rating }
            ];
        }
    },
    mounted() {
       // this.fetchStats();
        //this.fetchNextEvent();
    },
    methods: {
        async fetchStats() {
            try {
                const response = await axios.get('/api/stats');
                this.stats = response.data;
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        },
        async fetchNextEvent() {
            try {
                const response = await axios.get('/api/next-event');
                this.nextEvent = response.data;
            } catch (error) {
                console.error('Error fetching next event:', error);
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

