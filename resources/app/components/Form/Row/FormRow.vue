<script setup lang="ts">
import AppIcon from "Components/AppIcon/AppIcon.vue";

defineProps({
    refId: {
        type: String,
        default: Math.random().toString(36).substring(2)
    },
    label: String,
    required: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <div class="form-row">
        <label class="form-label" :for="refId">
            <span v-if="label?.length">{{ label }}</span>
            <app-icon name="required" v-if="required" v-tippy="{ content: 'Dieses Feld muss ausgefüllt werden.' }" />
            <span v-if="label?.length">:</span>
        </label>
        <slot name="input" />
    </div>
</template>
