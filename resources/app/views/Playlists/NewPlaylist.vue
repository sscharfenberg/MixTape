<script setup lang="ts">
import axios from "axios";
import AppButton from "Components/Form/Button/AppButton.vue";
import FormRow from "Components/Form/Row/FormRow.vue";
import AppWidget from "Components/Widget/AppWidget.vue";
import { push } from "notivue";
import { ref } from "vue";
const showForm = ref(false);
const newPlayListName = ref("");
const loading = ref(false);
const onSubmit = () => {
    loading.value = true;
    axios
        .post(`/api/playlists/new`, { name: newPlayListName.value })
        .then(response => {
            if (response.status === 200 && response.data.status === "success") {
                push.success({
                    message: response.data.message
                });
                showForm.value = false;
                newPlayListName.value = "";
                emit("new", response.data.newPlaylist);
            }
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.response.data.message || error.message
            });
        })
        .finally(() => {
            loading.value = false;
        });
};
const emit = defineEmits(["new"]);
</script>

<template>
    <div class="new-playlist">
        <app-button
            icon="playlist_add"
            text="Neue Playlist erstellen"
            :type="showForm ? 'primary' : 'default'"
            @click="showForm = !showForm"
        />
        <app-widget v-if="showForm" :loading="false" icon="playlist_add" :error="false" :refresh-button="false">
            <template #body>
                <form class="form-col">
                    <form-row ref-id="newPlayListName" label="Name" :required="true">
                        <template #input>
                            <input
                                class="form-input"
                                type="text"
                                placeholder="Name"
                                id="newPlayListName"
                                v-model="newPlayListName"
                                maxlength="128"
                                :readonly="loading ? true : null"
                            />
                        </template>
                    </form-row>
                    <form-row>
                        <template #input>
                            <app-button
                                icon="save"
                                icon-position="right"
                                text="Erstellen"
                                type="primary"
                                @click.prevent="onSubmit"
                                :disabled="newPlayListName.length === 0 || loading"
                                :loading="loading"
                            />
                        </template>
                    </form-row>
                </form>
            </template>
        </app-widget>
    </div>
</template>

<style lang="scss" scoped>
.widget {
    margin-top: 1lh;
}
</style>
