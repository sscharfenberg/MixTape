<script setup lang="ts">
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
</script>

<template>
    <div class="widget-loader">
        <loading-spinner :size="7" />
    </div>
</template>

<style lang="scss" scoped>
@use "sass:map";
@use "Abstracts/colors" as c;
@use "Abstracts/sizes" as s;

.widget-loader {
    display: flex;

    position: absolute;
    inset: map.get(s.$widget, "border");
    align-items: center;
    justify-content: center;

    background-color: map.get(c.$widget, "loader-background");
    color: map.get(c.$widget, "loader-surface");
    border-radius: inherit;
}
</style>
