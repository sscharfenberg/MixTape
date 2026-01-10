<script setup lang="ts">
import { formatBytes, formatSeconds } from "@/formatters/numbers";
import DataTablesCore from "datatables.net-dt";
import "datatables.net-responsive-dt";
import DataTable from "datatables.net-vue3";
import { useRouter } from "vue-router";
const router = useRouter();
DataTable.use(DataTablesCore);
defineProps({
    albums: {
        type: Array,
        required: true
    }
});
const dataTableOptions = {
    columns: [
        { data: "artist.name", title: "Künstler", orderData: [0, 1] },
        { data: "year", title: "Jahr" },
        { data: "name", title: "Album" },
        { data: "numSongs", title: "Songs" },
        {
            data: "duration",
            title: "Dauer",
            render: (data, type) => {
                if (type === "display" || type === "filter") {
                    return formatSeconds(data);
                }
                return data; // for all other types
            }
        },
        {
            data: "fileSize",
            title: "Dateigröße",
            render: (data, type) => {
                if (type === "display" || type === "filter") {
                    return formatBytes(data);
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
    order: [[0, "asc"]],
    responsive: true,
    pageLength: 25
};
</script>

<template>
    <DataTable :data="albums" class="display responsive" :options="dataTableOptions" />
</template>
