<template>
    <div class="review-form">
        <h2>Leave a Review</h2>
        <form @submit.prevent="submitReview">
            <div class="form-group">
                <label for="rating">Rating</label>
                <div class="stars">
                    <span v-for="star in 5" :key="star" class="star" @click="setRating(star)" :class="{ 'selected': star <= review.rating }">
                        &#9733;
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea v-model="review.comment" placeholder="Optional comment"></textarea>
            </div>
            <button type="submit">Submit Review</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            users: [],
            review: {
                reviewed_id: this.getUserIdFromUrl(),
                rating: 1,
                comment: ''
            }
        };
    },
    methods: {
        setRating(star) {
            this.review.rating = star;
        },
        getUserIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            return parseInt(pathParts[pathParts.length - 1], 10);
        },
        async submitReview() {
            try {
                console.log(this.review);
                const response = await axios.post('/api/reviews', this.review);
                this.resetForm();
                window.location.href = '/dashboard';
            } catch (error) {
                console.error('Error submitting review:', error);
                alert('There was an error submitting your review. Please try again.');
            }
        },
        resetForm() {
            this.review.rating = 1;
            this.review.comment = '';
        }
    }
};
</script>

<style scoped>
.review-form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
}

textarea,
input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.stars {
    display: flex;
}

.star {
    font-size: 2em;
    color: #ddd;
    cursor: pointer;
}

.star.selected {
    color: gold;
}

button {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}
</style>
