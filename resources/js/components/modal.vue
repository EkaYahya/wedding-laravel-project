<template>
    <div
        v-if="isVisible"
        class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50"
    >
        <div class="bg-white rounded-lg p-6 shadow-md w-1/3">
            <div class="text-center">
                <p v-if="type === 'success'" class="text-green-500 font-bold">
                    Success
                </p>
                <p v-if="type === 'error'" class="text-red-500 font-bold">
                    Error
                </p>
                <p>{{ message }}</p>
                <button
                    @click="closeModal"
                    class="mt-4 px-4 py-2 bg-primary-dark text-white rounded hover:bg-primary-light"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isVisible: false,
            type: "success", // 'success' or 'error'
            message: "", // Message to be displayed in the modal
        };
    },
    methods: {
        openModal(type, message) {
            this.type = type;
            this.message = message;
            this.isVisible = true;
        },
        closeModal() {
            this.isVisible = false;
        },
    },
};
</script>

<style scoped>
/* Styling untuk modal */
.bg-primary-dark {
    background-color: #1d4ed8;
}
.bg-primary-light {
    background-color: #3b82f6;
}
</style>
