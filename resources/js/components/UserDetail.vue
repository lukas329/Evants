<template>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="card" style="width: 100%;" v-if="user">
        <img src="https://images.pexels.com/photos/614810/pexels-photo-614810.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" class="card-img-top" alt="..." style="width: 300px; height: 400px">
        <div class="card-body">
            <h5 class="card-title">{{user.name}}</h5>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">{{ user.email }}</li>
        </ul>
        <div class="card-body">
            <button @click="startConversation" class="btn btn-primary">Start Conversation</button>
        </div>
        <div class="card-body">
            <button @click="leaveReview" class="btn btn-primary">Leave a review</button>
        </div>

    </div>
</template>

<script>
import axios from 'axios';
axios.defaults.withCredentials = true;
export default {
    data() {
        return {
            userId: null,
            user: [],
            errorMessage: ''
        };
    },
    mounted() {
        this.userId = this.getUserIdFromUrl();

        if (this.userId){
            this.fetchEvents();
        }
    },
    methods: {
        getUserIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            return pathParts[pathParts.length - 1];
        },
        fetchEvents() {
            console.log('fetch:', this.userId);
            axios
                .get(`/api/user/${this.userId}`)
                .then(response => {
                    this.user = response.data;
                    console.log(this.user);
                })
                .catch(error => {
                    console.error('Error fetching user:', error);
                    this.errorMessage = 'Error fetching events.';
                });
        },
        startConversation() {
            location.href = `/chat/${this.userId}`;
        },
        leaveReview() {
            location.href = `/review/${this.userId}`;
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
