<script setup lang="ts">
import AppButton from "Components/Form/Button/AppButton.vue";
import AppWidget from "Components/Widget/AppWidget.vue";
import { onMounted, onUnmounted } from "vue";
defineProps({
    title: {
        type: String,
        required: true
    }
});
const emit = defineEmits(["close"]);
const onClose = () => emit("close");
const onKeyDown = (e: KeyboardEvent) => {
    if (e.key === "Escape") {
        onClose();
    }
};
onMounted(() => {
    document.querySelector("body").addEventListener("keydown", onKeyDown);
});
onUnmounted(() => {
    document.querySelector("body").removeEventListener("keydown", onKeyDown);
});
</script>

<template>
    <Teleport to="#app main">
        <div class="modal__backdrop">
            <app-widget class="modal__window">
                <template #title
                    >{{ title }}
                    <app-button icon="close" :short="true" class="close-btn" @click="onClose" aria-label="Schließen"
                /></template>
                <template #body>
                    <slot />
                </template>
                <template #footer>
                    <slot name="footer" />
                </template>
            </app-widget>
        </div>
    </Teleport>
</template>

<style scoped lang="scss">
@use "sass:map";
@use "Abstracts/colors" as c;
@use "Abstracts/sizes" as s;
@use "Abstracts/mixins" as m;
@use "Abstracts/shadows" as sh;
@use "Abstracts/z-indexes" as z;

.modal {
    &__backdrop {
        display: flex;
        position: fixed;
        inset: 0;
        z-index: map.get(z.$index, "modal");
        align-items: center;
        justify-content: center;

        background-color: map.get(c.$main, "modal-backdrop-background");
        backdrop-filter: blur(map.get(s.$main, "modal-backdrop-blur"));
    }

    &__window {
        width: 100%;
        margin: map.get(s.$main, "modal-margin");

        background-color: map.get(c.$main, "modal-background");
        box-shadow: map.get(sh.$main, "modal");

        @include m.mq("portrait") {
            max-width: map.get(s.$main, "modal-max-width");
        }

        .close-btn {
            align-self: flex-start;

            margin-left: auto;
        }
    }
}
</style>
