<template>
    <router-view v-slot="{ Component }">
        <main>
            <component :is="Component" />
        </main>
    </router-view>
</template>

<style lang="scss" scoped>
main {
    margin: 3ch 0;
}
</style>
