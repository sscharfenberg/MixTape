<script setup lang="ts">
import { formatBytes, formatSeconds } from "@/formatters/numbers";
import DataTablesCore from "datatables.net-dt";
import "datatables.net-responsive-dt";
import DataTable from "datatables.net-vue3";
import { useRouter } from "vue-router";
const router = useRouter();
DataTable.use(DataTablesCore);
defineProps({
    artists: {
        type: Array,
        required: true
    }
});
const dataTableOptions = {
    columns: [
        { data: "name", title: "Name" },
        { data: "numAlbums", title: "Alben" },
        { data: "numSongs", title: "Songs" },
        {
            data: "songsDuration",
            title: "Dauer",
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
        }
    ],
    fnRowCallback: (nRow, aData) => {
        nRow.addEventListener("click", () => {
            router.push({
                name: "artist",
                params: { id: aData.encodedName }
            });
        });
    },
    order: [[3, "desc"]],
    responsive: true,
    pageLength: 25
};
</script>

<template>
    <DataTable :data="artists" class="display responsive" :options="dataTableOptions" />
</template>
