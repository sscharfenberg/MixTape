<script setup lang="ts">
import { formatBytes, formatSeconds } from "@/formatters/numbers";
import axios from "axios";
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import DataTablesCore from "datatables.net-dt";
import "datatables.net-responsive-dt";
import DataTable from "datatables.net-vue3";
import { push } from "notivue";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
DataTable.use(DataTablesCore);
const isLoading = ref(false);
const data = ref(null);
const hasError = ref(false);
const router = useRouter();
const fetchData = () => {
    data.value = null;
    isLoading.value = true;
    hasError.value = false;
    axios
        .get(`/api/music/artists`)
        .then(response => {
            data.value = response.data;
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.response.data.message || error.message
            });
            hasError.value = true;
        })
        .finally(() => {
            isLoading.value = false;
        });
};
onMounted(() => {
    fetchData();
});
const dataTableOptions = {
    columns: [
        { data: "name", title: "Name" },
        { data: "numSongs", title: "Songs", type: "num" },
        {
            data: "songsDuration",
            title: "Laufzeit",
            render: (data, type) => {
                if (type === "display" || type === "filter") {
                    return formatSeconds(data);
                }
                return data; // for all other types
            }
        },
        {
            data: "songsFileSize",
            title: "Dateigröße",
            render: (data, type) => {
                if (type === "display" || type === "filter") {
                    return formatBytes(data);
                }
                return data; // for all other types
            }
        },
        { data: "albums.length", title: "Alben" }
    ],
    fnRowCallback: (nRow, aData) => {
        nRow.addEventListener("click", () => {
            router.push({
                name: "artist",
                params: { id: aData.encodedName }
            });
        });
    },
    order: [[2, "desc"]],
    responsive: true,
    pageLength: 25
};
</script>

<template>
    <section class="widget">
        <div class="loading-spinner__outer" v-if="isLoading">
            <loading-spinner :size="8" />
        </div>
        <show-error v-else-if="hasError && !isLoading" @refresh="fetchData()" />
        <div v-else class="artists">
            <DataTable :data="data" class="display responsive" :options="dataTableOptions"></DataTable>
        </div>
    </section>
</template>

<style lang="scss" scoped>
section {
    min-height: 120px;
}

.artists {
    display: flex;
    flex-direction: column;

    gap: 2ch;
}
</style>
