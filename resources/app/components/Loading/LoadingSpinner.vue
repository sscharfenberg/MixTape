<script setup lang="ts">
const props = defineProps({
    size: {
        type: Number,
        default: 4
    },
    text: {
        type: String,
        default: ""
    }
});
const pixelSize = `${props.size * 12}px`;
</script>

<template>
    <span class="loading-wrapper">
        <span class="loading-spinner"></span>
        <span v-if="text.length">{{ text }}</span>
    </span>
</template>

<style scoped lang="scss">
.loading-wrapper {
    display: flex;
    align-items: center;

    gap: 1ch;
}

.loading-spinner {
    --pixel-size: v-bind(pixelSize);
}
</style>
