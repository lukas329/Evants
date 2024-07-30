<template>
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
        <!-- Button -->
        <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                Send
            </button>
        </span>
    </div>
</template>

<script>
export default {
    props: ["user"],
    data() {
        return {
            newMessage: "",
            recipientId: 1 // Set a default recipient ID or pass it dynamically
        };
    },
    methods: {
        sendMessage() {
            this.$emit("messagesent", {
                user: this.user,
                message: this.newMessage,
                recipient_id: this.recipientId // Add recipient_id to the emitted event
            });
            this.newMessage = "";
        },
    },
};
</script>
