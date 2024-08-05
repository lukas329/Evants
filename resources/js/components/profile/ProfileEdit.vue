<template>
    <div class="container mt-5">
        <h2>User Profile</h2>
        <div v-if="!editMode">
            <div class="profile-info">
                <img :src="profilePictureUrl" alt="Profile Picture" class="profile-picture" v-if="user.profile_picture">
                <p><strong>Name:</strong> {{ user.name }}</p>
                <p><strong>Email:</strong> {{ user.email }}</p>
                <p><strong>Date of Birth:</strong> {{ user.date_of_birth }}</p>
                <p><strong>Phone:</strong> {{ user.phone }}</p>
                <p><strong>Status:</strong> {{ user.status }}</p>
                <p><strong>Bio:</strong> {{ user.bio }}</p>
                <p><strong>Social Media Links:</strong> {{ user.social_media_links }}</p>
                <button class="btn btn-primary" @click="editMode = true">Edit</button>
            </div>
        </div>
        <div v-else>
            <form @submit.prevent="saveProfile">
                <div class="form-group">
                    <label for="profile_picture">Profile Picture</label>
                    <input type="file" id="profile_picture" @change="onFileChange" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" v-model="form.name" class="form-control">
                    <div v-if="validationErrors.name" class="text-danger">{{ validationErrors.name[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" v-model="form.email" class="form-control">
                    <div v-if="validationErrors.email" class="text-danger">{{ validationErrors.email[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" v-model="form.date_of_birth" class="form-control" required>
                    <div v-if="validationErrors.date_of_birth" class="text-danger">{{ validationErrors.date_of_birth[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" v-model="form.phone" class="form-control">
                    <div v-if="validationErrors.phone" class="text-danger">{{ validationErrors.phone[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" v-model="form.bio" class="form-control"></textarea>
                    <div v-if="validationErrors.bio" class="text-danger">{{ validationErrors.bio[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="social_media_links">Social Media Links</label>
                    <input type="text" id="social_media_links" v-model="form.social_media_links" class="form-control">
                    <div v-if="validationErrors.social_media_links" class="text-danger">{{ validationErrors.social_media_links[0] }}</div>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-secondary" @click="cancelEdit">Cancel</button>
            </form>
        </div>
        <div v-if="errorMessage" class="alert alert-danger mt-3">{{ errorMessage }}</div>
        <div v-if="successMessage" class="alert alert-success mt-3">{{ successMessage }}</div>
    </div>
</template>

<script>
import axios from 'axios';
axios.defaults.withCredentials = true;

export default {
    data() {
        return {
            user: {},
            editMode: false,
            form: {
                profile_picture: null,
                name: '',
                email: '',
                date_of_birth: null,
                phone: null,
                role: 'user',
                status: 'unverified',
                bio: null,
                social_media_links: null
            },
            errorMessage: '',
            successMessage: '',
            validationErrors: {}
        };
    },
    computed: {
        profilePictureUrl() {
            return this.user.profile_picture ? `/storage/${this.user.profile_picture}` : '';
        }
    },
    created() {
        this.fetchUserProfile();
    },
    methods: {
        fetchUserProfile() {
            axios
                .get('/api/user')
                .then(response => {
                    this.user = response.data;
                    this.form = {
                        ...response.data,
                        social_media_links: response.data.social_media_links ? response.data.social_media_links.join(', ') : null
                    };
                })
                .catch(error => {
                    console.error('Error fetching user profile:', error);
                    this.errorMessage = 'Error fetching user profile.';
                });
        },
        saveProfile() {
            const formData = new FormData();
            Object.keys(this.form).forEach(key => {
                if (key === 'social_media_links') {
                    if (this.form[key]) {
                        formData.append(key, JSON.stringify(this.form[key].split(',').map(link => link.trim())));
                    } else {
                        formData.append(key, null);
                    }
                } else if (key === 'profile_picture' && this.form[key] instanceof File) {
                    formData.append(key, this.form[key]);
                } else {
                    formData.append(key, this.form[key] === '' ? null : this.form[key]);
                }
            });

            axios
                .post('/api/user', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    this.successMessage = 'Profile updated successfully!';
                    this.editMode = false;
                    this.fetchUserProfile();
                })
                .catch(error => {
                    console.error('Error updating profile:', error);
                    if (error.response && error.response.data) {
                        if (error.response.data.errors) {
                            this.validationErrors = error.response.data.errors;
                        }
                    }
                    this.errorMessage = 'Error updating profile.';
                });
        },
        cancelEdit() {
            this.editMode = false;
            this.form = {
                ...this.user,
                social_media_links: this.user.social_media_links ? this.user.social_media_links.join(', ') : null
            };
        },
        onFileChange(event) {
            const file = event.target.files[0];
            this.form.profile_picture = file;
        }
    }
};
</script>

<style scoped>
.container {
    max-width: 600px;
    margin: auto;
}

.profile-info {
    text-align: center;
}

.profile-picture {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 15px;
}

.form-group {
    margin-bottom: 15px;
}
</style>
