<script setup lang="ts">
import AppButton from "Components/Form/Button/AppButton.vue";
import FormRow from "Components/Form/Row/FormRow.vue";
import AppWidget from "Components/Widget/AppWidget.vue";
import { ref } from "vue";
const showForm = ref(true);
const newPlayListName = ref("");
</script>

<template>
    <app-button
        icon="playlist_add"
        text="Neue Playlist erstellen"
        :type="showForm ? 'primary' : 'default'"
        @click="showForm = !showForm"
    />
    <app-widget v-if="showForm" :loading="false" icon="playlist_add" :error="false" :refresh-button="false">
        <template #title>Neue Playlist</template>
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
                        />
                    </template>
                </form-row>
                <form-row ref-id="newPlayListName">
                    <template #input>
                        <app-button icon="playlist_add" text="Erstellen" type="primary" />
                    </template>
                </form-row>
            </form>
        </template>
    </app-widget>
</template>
