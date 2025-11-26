<script setup lang="ts">
import ShowError from "Components/Error/ShowError.vue";
import WidgetBody from "./WidgetBody.vue";
import WidgetFooter from "./WidgetFooter.vue";
import WidgetLoader from "./WidgetLoader.vue";
import WidgetTitle from "./WidgetTitle.vue";
defineProps({
    loading: {
        type: Boolean,
        default: false
    },
    icon: {
        type: String
    },
    error: {
        type: Boolean,
        default: false
    },
    refreshButton: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <div class="widget">
        <widget-title v-if="$slots.title" :icon="icon" :refresh-button="refreshButton" @refresh="$emit('refresh')">
            <slot name="title" />
        </widget-title>
        <widget-body v-if="$slots.body && !loading && !error">
            <slot name="body" />
        </widget-body>
        <widget-footer v-if="$slots.footer">
            <slot name="footer" />
        </widget-footer>
        <widget-loader v-if="loading" />
        <show-error v-if="error && !loading" @refresh="$emit('refresh')" />
    </div>
</template>
