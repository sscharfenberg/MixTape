<template>
    <h1>
        <span>MixTape</span>
        <span>MixTape</span>
    </h1>
</template>

<style scoped lang="scss">
@use "sass:map";
@use "Abstracts/mixins" as m;
@use "Abstracts/sizes" as s;
@use "Abstracts/colors" as c;
@use "Abstracts/shadows" as sh;

h1 {
    position: relative;

    padding: 0;
    margin: 0;

    transform: skew(-15deg);

    font-weight: 900;
    line-height: 1;
    letter-spacing: 0.03em;

    @include m.mqset(
        "font-size",
        #{map.get(s.$header, "font-size", "base")},
        #{map.get(s.$header, "font-size", "portrait")},
        #{map.get(s.$header, "font-size", "landscape")},
        #{map.get(s.$header, "font-size", "desktop")}
    );

    span:first-child {
        display: block;

        text-shadow: map.get(sh.$header, "title");
        -webkit-text-stroke: 0.06em rgb(black, 0.5);
    }

    span:last-child {
        position: absolute;
        top: 0;
        left: 0;

        background-image: map.get(c.$header, "title");
        background-clip: text;
        -webkit-text-stroke: 0.01em #94a0b9;
        -webkit-text-fill-color: transparent;
    }
}
</style>
