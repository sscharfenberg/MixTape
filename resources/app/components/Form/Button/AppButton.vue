<script setup lang="ts">
import AppIcon from "Components/AppIcon/AppIcon.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
const props = defineProps({
    disabled: {
        type: Boolean,
        default: false
    },
    icon: {
        type: String
    },
    text: {
        type: String
    },
    iconPosition: {
        type: String,
        default: "left",
        validator: value => ["left", "right"].includes(value)
    },
    type: {
        type: String,
        default: "default",
        validator: value => ["primary", "default"].includes(value)
    },
    short: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    }
});
const cssClasses = () => {
    const classes = ["btn"];
    classes.push(props.type);
    if (props.short) classes.push("short");
    return classes.join(" ");
};
</script>

<template>
    <button
        :class="cssClasses()"
        :disabled="disabled || loading ? 'true' : null"
        :aria-disabled="disabled || loading ? 'true' : null"
    >
        <app-icon v-if="icon && icon.length && iconPosition === 'left'" :name="icon" />
        <span v-if="text && text.length">{{ text }}</span>
        <app-icon v-if="icon && icon.length && iconPosition === 'right'" :name="icon" />
        <loading-spinner v-if="loading" :size="2" />
    </button>
</template>
