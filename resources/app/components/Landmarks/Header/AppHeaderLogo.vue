<template>
    <router-link :to="{ name: 'dashboard' }">
        <img src="./logo.svg" alt="MixTape logo" />
    </router-link>
</template>

<style scoped lang="scss">
@use "sass:map";
@use "Abstracts/mixins" as m;
@use "Abstracts/sizes" as s;

img {
    width: 100%;

    @include m.mqset(
        "height",
        #{map.get(s.$header, "logo-height", "base")},
        #{map.get(s.$header, "logo-height", "portrait")},
        #{map.get(s.$header, "logo-height", "landscape")},
        #{map.get(s.$header, "logo-height", "desktop")}
    );
}
</style>
