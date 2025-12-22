<script setup lang="ts">
import { formatSeconds } from "@/formatters/numbers";
import DataTablesCore from "datatables.net-dt";
import "datatables.net-responsive-dt";
import DataTable from "datatables.net-vue3";
import { useRouter } from "vue-router";
const router = useRouter();
DataTable.use(DataTablesCore);
defineProps({
    songs: {
        type: Array,
        required: true
    }
});
const dataTableOptions = {
    columns: [
        { data: "name", title: "Name" },
        { data: "artist.name", title: "Artist", orderData: [1, 3, 2, 5, 4] },
        { data: "album.name", title: "Album" },
        { data: "album.year", title: "Jahr", type: "num" },
        {
            data: "track",
            title: "Track",
            render: (data, type, row) => {
                if (type === "display" || type === "filter") {
                    if (row.track <= row.album.discTracks) {
                        return row.track + "/" + row.album.discTracks;
                    }
                    return row.track;
                }
                return data;
            }
        },
        {
            data: "disc",
            title: "CD",
            render: (data, type, row) => {
                if (row.disc && row.album.discs) {
                    return row.disc + "/" + row.album.discs;
                }
                return "-";
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
                name: "song",
                params: { id: aData.encodedPath }
            });
        });
    },
    order: [[1, "asc"]],
    responsive: true,
    pageLength: 25
};
</script>

<template>
    <DataTable :data="songs" class="display responsive" :options="dataTableOptions" />
</template>
