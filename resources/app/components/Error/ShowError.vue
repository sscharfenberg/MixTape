<script setup lang="ts">
import AppButton from "Components/Button/AppButton.vue";
</script>

<template>
    <div class="error">
        Oops. Da ist was schiefgelaufen.
        <app-button text="Neu laden" icon="refresh" type="primary" @click="$emit('refresh')" />
    </div>
</template>

<style scoped lang="scss">
.error {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    gap: 1ch;
}
</style>
