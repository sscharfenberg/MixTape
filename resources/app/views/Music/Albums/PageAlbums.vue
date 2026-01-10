<script setup lang="ts">
import { formatSeconds } from "@/formatters/numbers";
import axios from "axios";
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import DataTablesCore from "datatables.net-dt";
import "datatables.net-responsive-dt";
import DataTable from "datatables.net-vue3";
import { format } from "date-fns";
import { push } from "notivue";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
DataTable.use(DataTablesCore);
const isLoading = ref(false);
const data = ref(null);
const minSongs = ref(0);
const hasError = ref(false);
const router = useRouter();
const fetchData = () => {
    data.value = null;
    isLoading.value = true;
    hasError.value = false;
    axios
        .get(`/api/music/albums`)
        .then(response => {
            data.value = response.data.albums;
            minSongs.value = response.data.minSongs;
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
        { data: "year", title: "Jahr", type: "num" },
        { data: "name", title: "Name" },
        { data: "artist.name", title: "Artist", orderData: [2, 0] },
        { data: "numSongs", title: "Tracks" },
        { data: "discs", title: "CDs" },
        {
            data: "modifiedAt",
            title: "Letzte Änderung",
            render: (data, type) => {
                if (type === "display" || type === "filter") {
                    return format(data, "dd.MM.yyyy HH:mm:ss");
                }
                return data; // for all other types
            }
        },
        {
            data: "duration",
            title: "Dauer",
            render: (data, type) => {
                if (type === "display" || type === "filter") {
                    return formatSeconds(data);
                }
                return data; // for all other types
            }
        }
    ],
    fnRowCallback: (nRow, aData) => {
        nRow.addEventListener("click", () => {
            router.push({
                name: "album",
                params: { id: aData.encodedNames }
            });
        });
    },
    order: [[0, "desc"]],
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
        <div v-else class="genres">
            <p>Es werden nur Alben mit mindestens {{ minSongs }} Songs angezeigt.</p>
            <DataTable :data="data" class="display responsive" :options="dataTableOptions"></DataTable>
        </div>
    </section>
</template>

<style lang="scss" scoped>
section {
    min-height: 120px;
}

.albums {
    display: flex;
    flex-direction: column;

    gap: 2ch;
}
</style>
