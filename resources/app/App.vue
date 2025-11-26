<script setup lang="ts">
import AppFooter from "Components/Landmarks/Footer/AppFooter.vue";
import AppHeader from "Components/Landmarks/Header/AppHeader.vue";
import AppMain from "Components/Landmarks/Main/AppMain.vue";
import { Notification, NotificationProgress, Notivue, lightTheme, type NotivueTheme } from "notivue";

const theme: NotivueTheme = {
    ...lightTheme,
    "--nv-global-bg": "light-dark(#dadada, #383839)", // Container background
    "--nv-global-fg": "light-dark(#383839, #dadada)", // Title, message, close icon color
    "--nv-global-accent": "#bada55", // Icon and tip color
    "--nv-global-border": "#f00" // Container border color
};
</script>

<template>
    <Notivue v-slot="item">
        <Notification :item="item" :theme="theme">
            <NotificationProgress :item="item" />
        </Notification>
    </Notivue>
    <app-header />
    <app-main />
    <app-footer />
</template>
