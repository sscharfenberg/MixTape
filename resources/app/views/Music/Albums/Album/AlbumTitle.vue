<script setup lang="ts">
defineProps({
    title: {
        type: String,
        required: true
    },
    cover: String
});
</script>

<template>
    <header class="details-title">
        <div class="details-title__title">
            <h2>{{ title }}</h2>
        </div>
        <img v-if="cover && cover.length === 40" :src="`/storage/${cover}`" :alt="title" />
    </header>
</template>
