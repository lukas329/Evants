<template>
    <div class="chat-container">
        <div class="chat-window">
            <ul class="chat" ref="chatContainer">
                <li v-for="message in messages" :key="message.id" :class="{'right': !isCurrentUser(message.sender.id), 'left': isCurrentUser(message.sender.id)}">
                    <div class="clearfix">
                        <div class="header">
                            <strong>{{ message.sender.name }}</strong>
                        </div>
                        <p :class="{'my-message': !isCurrentUser(message.sender.id), 'his-message': isCurrentUser(message.sender.id)}">
                            {{ message.content }}
                        </p>
                    </div>
                </li>
            </ul>
        </div>
        <div class="input-group">
            <input
                id="btn-input"
                type="text"
                name="message"
                class="form-control input-sm"
                placeholder="Type your message here..."
                v-model="newMessage"
                @keyup.enter="sendMessage"
            />
            <span class="input-group-btn">
                <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                    Send
                </button>
            </span>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            userId: null,
            messages: [], // Store chat messages for display in this array
            newMessage: "",
            recipientId: 1 // Set a default recipient ID or pass it dynamically
        };
    },
    mounted() {
        this.userId = this.getUserIdFromUrl();
        if (this.userId){
            this.fetchMessages();
        }
        this.scrollToBottom();
    },
    updated() {
        this.scrollToBottom();
    },
    created() {
        this.fetchMessages(); // Upon initialization, run fetchMessages()
    },
    methods: {
        getUserIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            return parseInt(pathParts[pathParts.length - 1], 10);
        },
        async fetchMessages() {
            await axios
                .get(`/api/chat/${this.userId}`)
                .then(response => {
                    this.messages = response.data;
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                    this.errorMessage = 'Error fetching messages.';
                });
        },
        addMessage(message) {
            // Pushes it to the messages array
            // POST request to the messages route with the message data in order for our Laravel server to broadcast it.
            axios.post('/send-message', message).then(response => {
                this.fetchMessages();
            });
        },
        sendMessage() {
            if (!this.newMessage.trim()) {
                console.error('Message content is empty.');
                return;
            }
            const messageData = {
                content: this.newMessage,
                recipient_id: this.userId
            };
            this.addMessage(messageData);
            this.newMessage = "";
            this.$nextTick(() => {
                this.scrollToBottom();
            });
        },
        isCurrentUser(userId) {
            return userId !== this.userId;
        },
        scrollToBottom() {
            const chatContainer = this.$refs.chatContainer;
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    }
};
</script>

<style scoped>
.chat-container {
    display: flex;
    flex-direction: column;
    padding-top: 10px;
    height: 70vh;
}

.chat-window {
    flex: 1;
    overflow-y: scroll;
    background: white;
    border-top: 1px solid #ccc;
    padding: 10px;
}

.chat {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.chat li {
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
}

.chat .right {
    align-items: flex-end;
}

.chat .left {
    align-items: flex-start;
}

.my-message {
    background-color: purple;
    color: white;
    padding: 10px;
    border-radius: 10px;
    display: inline-block;
    max-width: 40%; /* Maximum width of 40% */
    word-wrap: break-word;
}

.his-message {
    background-color: grey;
    color: white;
    padding: 10px;
    border-radius: 10px;
    display: inline-block;
    max-width: 40%; /* Maximum width of 40% */
    word-wrap: break-word;
}

.clearfix{
    min-width: 50%;
    max-width: 50%;
}

.input-group {
    display: flex;
    padding: 10px;
    border-top: 1px solid #ccc;
}

#btn-input {
    flex: 1;
    padding: 10px;
}

.input-group-btn {
    margin-left: 10px;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
    line-height: 1.5;
}
</style>

