<script setup lang="ts">
import AppIcon from "Components/AppIcon/AppIcon.vue";
import AppButton from "Components/Form/Button/AppButton.vue";
defineProps({
    icon: {
        type: String
    },
    refreshButton: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <header class="widget-title">
        <app-icon v-if="icon" :name="icon" :size="2" />
        <slot />
        <app-button
            v-if="refreshButton"
            icon="refresh"
            type="default"
            @click.prevent="$emit('refresh')"
            aria-label="Kachel neu laden"
        />
    </header>
</template>

<style lang="scss" scoped>
@use "sass:map";
@use "Abstracts/sizes" as s;
@use "Abstracts/mixins" as m;

.widget-title {
    display: flex;
    align-items: center;

    gap: 8px;

    line-height: 1.4;

    @include m.mqset(
        "font-size",
        #{map.get(s.$widget, "title-font-size", "base")},
        #{map.get(s.$widget, "title-font-size", "portrait")},
        #{map.get(s.$widget, "title-font-size", "landscape")},
        #{map.get(s.$widget, "title-font-size", "desktop")}
    );

    button {
        margin-left: auto;
    }
}
</style>
