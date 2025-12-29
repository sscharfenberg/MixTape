<script setup lang="ts">
import { useWidgetStore } from "@/stores/widgetStore";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import AppButton from "Components/Form/Button/AppButton.vue";
import { computed } from "vue";
const widgetStore = useWidgetStore();
const shuffleActive = computed({
    get: () => widgetStore.toggles[props.toggleName],
    set: isShuffle => {
        widgetStore.toggles[props.toggleName] = isShuffle;
    }
});
const emit = defineEmits(["refresh"]);
const props = defineProps({
    icon: {
        type: String
    },
    refreshButton: {
        type: Boolean,
        default: false
    },
    toggleName: String
});
const onShuffle = () => {
    shuffleActive.value = !shuffleActive.value;
    emit("refresh");
};
</script>

<template>
    <div class="widget-title">
        <app-icon v-if="icon" :name="icon" :size="2" />
        <slot />
        <div class="actions">
            <app-button
                v-if="toggleName?.length > 0"
                icon="shuffle"
                type="default"
                @click.prevent="onShuffle"
                :class="{ active: shuffleActive }"
                :aria-label="shuffleActive ? 'Zufällige Einträge' : 'Sortierte Einträge'"
                v-tippy="{
                    content: shuffleActive
                        ? 'Zufällige Einträge. Ändern auf sortierte Einträge.'
                        : 'Sortierte Einträge. Ändern auf zufällige Einträge.'
                }"
            />
            <app-button
                v-if="refreshButton"
                icon="refresh"
                type="default"
                @click.prevent="$emit('refresh')"
                aria-label="Kachel neu laden"
            />
        </div>
    </div>
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

    .actions {
        display: flex;

        margin-left: auto;
        gap: 0.5ch;
    }
}
</style>
