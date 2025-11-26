<script setup lang="ts">
import WidgetAlbums from "Components/Widgets/WidgetAlbums.vue";
import WidgetGenres from "Components/Widgets/WidgetGenres.vue";
import WidgetSongs from "Components/Widgets/WidgetSongs.vue";
</script>

<template>
    <div class="widget-grid">
        <widget-songs />
        <widget-genres />
        <widget-albums />
    </div>
</template>
