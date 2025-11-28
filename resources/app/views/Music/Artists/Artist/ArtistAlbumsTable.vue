<script setup lang="ts">
import { formatBytes, formatSeconds } from "@/formatters/numbers";
import DataTablesCore from "datatables.net-dt";
import "datatables.net-responsive-dt";
import DataTable from "datatables.net-vue3";
import { useRouter } from "vue-router";
DataTable.use(DataTablesCore);
const router = useRouter();
defineProps({
    albums: {
        type: Array,
        required: true
    }
});
const dataTableOptions = {
    columns: [
        { data: "name", title: "Name" },
        { data: "year", title: "Jahr", type: "num" },
        { data: "numSongs", title: "Tracks" },
        { data: "discs", title: "CDs" },
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
            data: "size",
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
                params: { id: aData.id }
            });
        });
    },
    order: [[1, "desc"]],
    responsive: true,
    pageLength: 25
};
</script>

<template>
    <DataTable v-if="albums.length" :data="albums" class="display responsive" :options="dataTableOptions" />
</template>
