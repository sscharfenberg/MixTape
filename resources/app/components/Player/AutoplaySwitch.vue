<script setup lang="ts">
import { usePlayerStore } from "@/stores/playerStore";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import { computed } from "vue";
const store = usePlayerStore();
const autoPlay = computed({
    get: () => store.autoplay,
    set: newValue => {
        store.autoplay = newValue;
    }
});
</script>

<template>
    <div class="toggle-switch" v-tippy="{ content: 'Toggle Autoplay' }">
        <input
            class="toggle-switch__input"
            type="checkbox"
            name="autoplay"
            id="autoPlaySwitch"
            value="on"
            v-model="autoPlay"
        />
        <label class="toggle-switch__label" for="autoPlaySwitch">
            <span class="toggle-switch__knob">
                <app-icon name="autoplay" class="toggle-switch--on" />
                <app-icon name="pause" class="toggle-switch--off" />
            </span>
        </label>
    </div>
</template>
