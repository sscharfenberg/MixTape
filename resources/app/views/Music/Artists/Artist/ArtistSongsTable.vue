<script setup lang="ts">
import { formatBytes, formatSeconds } from "@/formatters/numbers";
import DataTablesCore from "datatables.net-dt";
import "datatables.net-responsive-dt";
import DataTable from "datatables.net-vue3";
import { useRouter } from "vue-router";
DataTable.use(DataTablesCore);
const router = useRouter();
defineProps({
    songs: {
        type: Array,
        required: true
    }
});
const dataTableOptions = {
    columns: [
        { data: "name", title: "Name" },
        { data: "album.name", title: "Album", orderData: [2, 1, 4, 3] },
        { data: "album.year", title: "Jahr", type: "num" },
        {
            data: "track",
            title: "Track",
            render: (data, type, row) => {
                if (type === "display" || type === "filter") {
                    return row.track + "/" + row.album.discTracks;
                }
                return data;
            }
        },
        {
            data: "disc",
            title: "CD",
            render: (data, type, row) => {
                if (type === "display" || type === "filter") {
                    if (row.disc && row.album.discs) {
                        return row.disc + "/" + row.album.discs;
                    }
                    return "";
                }
                return data;
            }
        },
        {
            data: "duration",
            title: "Dauer",
            render: (data, type) => {
                if (type === "display" || type === "filter") {
                    return "<nobr>" + formatSeconds(data) + "</nobr>";
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
    <DataTable v-if="songs.length" :data="songs" class="display responsive" :options="dataTableOptions" />
</template>
