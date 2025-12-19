<script setup lang="ts">
import { useRouteBreadcrumbs } from "@/router/breadcrumbs";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import { useRoute } from "vue-router";
const breadcrumbs = useRouteBreadcrumbs();
const route = useRoute();
</script>

<template>
    <nav class="breadcrumb" v-if="route.name !== 'dashboard'">
        <router-link :to="{ name: 'dashboard' }" v-tippy="{ content: `Dashboard` }" aria-label="Startseite">
            <app-icon name="home" />
        </router-link>
        <router-link
            v-for="item in breadcrumbs"
            :key="item.name"
            :to="{ name: item.name }"
            v-tippy="{ content: item.title }"
        >
            <app-icon :name="item.icon" />
            <span>{{ item.title }}</span>
        </router-link>
    </nav>
</template>

<style lang="scss" scoped>
@use "sass:map";
@use "Abstracts/colors" as c;
@use "Abstracts/sizes" as s;
@use "Abstracts/mixins" as m;
@use "Abstracts/shadows" as sh;

.breadcrumb {
    display: inline-flex;
    align-self: flex-start;

    overflow: hidden;

    margin-bottom: 1ch;

    border-radius: map.get(s.$main, "breadcrumb", "radius");

    box-shadow: map.get(sh.$main, "breadcrumb");

    a {
        display: flex;
        position: relative;

        float: left;
        align-items: center;

        height: map.get(s.$main, "breadcrumb", "height");

        padding: map.get(s.$main, "breadcrumb", "padding");
        gap: 1ch;

        background-color: map.get(c.$main, "breadcrumb-item-background");
        color: map.get(c.$main, "breadcrumb-item-surface");
        outline: none;

        line-height: map.get(s.$main, "breadcrumb", "height");
        text-decoration: none;

        &:not(:last-child)::after {
            display: block;
            position: absolute;
            top: 0;
            right: -#{map.get(s.$main, "breadcrumb", "height") * 0.5};

            z-index: 1;

            width: map.get(s.$main, "breadcrumb", "height");
            height: map.get(s.$main, "breadcrumb", "height");
            transform: scale(0.707) rotate(45deg);

            background-color: map.get(c.$main, "breadcrumb-item-background");
            border-radius: 0 5px 0 50px;

            box-shadow:
                2px -2px 0 2px rgb(255 255 255 / 80%),
                3px -3px 0 2px rgb(0 0 0 / 20%);

            content: "";

            @include m.mq("landscape") {
                right: -#{map.get(s.$main, "breadcrumb", "height-landscape") * 0.5};

                width: map.get(s.$main, "breadcrumb", "height-landscape");
                height: map.get(s.$main, "breadcrumb", "height-landscape");
            }
        }

        &:last-child {
            padding-right: 12px;
            padding-left: 24px;

            @include m.mq("landscape") {
                padding-right: 16px;
                padding-left: 30px;
            }
        }

        &:first-child {
            padding-left: 14px;

            @include m.mq("landscape") {
                padding-left: 20px;
            }
        }

        &.router-link-exact-active {
            background-color: map.get(c.$main, "breadcrumb-item-background-active");
            color: map.get(c.$main, "breadcrumb-item-surface-active");
        }

        &:hover {
            background-color: map.get(c.$main, "breadcrumb-item-background-hover");
            color: map.get(c.$main, "breadcrumb-item-surface-hover");

            &::after {
                background-color: map.get(c.$main, "breadcrumb-item-background-hover");
                color: map.get(c.$main, "breadcrumb-item-surface-hover");
            }
        }

        > span {
            display: none;

            @include m.mq("landscape") {
                display: block;
            }
        }

        @include m.mq("landscape") {
            height: map.get(s.$main, "breadcrumb", "height-landscape");
            padding: map.get(s.$main, "breadcrumb", "padding-landscape");

            line-height: map.get(s.$main, "breadcrumb", "height-landscape");
        }
    }
}

@include m.theme-dark(".breadcrumb a:not(:last-child)::after") {
    box-shadow:
        2px -2px 0 2px rgb(0 0 0 / 70%),
        3px -3px 0 2px rgb(255 255 255 / 30%);
}
</style>
