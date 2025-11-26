<script setup lang="ts">
defineProps({
    title: {
        type: String,
        required: true
    }
});
</script>

<template>
    <header class="details-title">
        <div class="details-title__title">
            <h2>{{ title }}</h2>
        </div>
    </header>
</template>
