<script setup lang="ts">
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import DataTable from "datatables.net-vue3";
</script>

<template>
    <section class="widget">
        <div class="loading-spinner__outer" v-if="isLoading">
            <loading-spinner :size="8" />
        </div>
        <show-error v-else-if="hasError && !isLoading" @refresh="fetchData()" />
        <div v-else class="genres">
            <DataTable :data="data" class="display responsive" :options="dataTableOptions"></DataTable>
        </div>
    </section>
</template>
