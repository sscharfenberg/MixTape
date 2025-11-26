<script setup lang="ts">
import StatWidgetAlbums from "Components/Widget/StatWidgets/StatWidgetAlbums.vue";
import StatWidgetGenres from "Components/Widget/StatWidgets/StatWidgetGenres.vue";
import StatWidgetGlobal from "Components/Widget/StatWidgets/StatWidgetGlobal.vue";
import StatWidgetSongs from "Components/Widget/StatWidgets/StatWidgetSongs.vue";
</script>

<template>
    <div class="widget-grid">
        <stat-widget-global />
        <stat-widget-songs />
        <stat-widget-genres />
        <stat-widget-albums />
    </div>
</template>
