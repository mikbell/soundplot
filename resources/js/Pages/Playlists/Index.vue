<template>
    <form @submit.prevent="addToPlaylist">
        <div>
            <label for="playlist">Seleziona una Playlist:</label>
            <select id="playlist" v-model="playlistId" required>
                <option
                    v-for="playlist in playlists"
                    :key="playlist.id"
                    :value="playlist.id"
                >
                    {{ playlist.name }}
                </option>
            </select>
        </div>

        <div>
            <label for="videoId">ID del Video:</label>
            <input
                id="videoId"
                type="text"
                v-model="manualVideoId"
                placeholder="Inserisci l'ID del video"
                required
            />
        </div>

        <button type="submit">Aggiungi</button>
    </form>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

// Props ricevuti da Inertia
defineProps({
    playlists: Array,
});

// Variabili reattive
const playlistId = ref(null);
const manualVideoId = ref(""); // Campo per l'ID del video

const addToPlaylist = () => {
    if (!playlistId.value || !manualVideoId.value) {
        alert("Seleziona una playlist e inserisci l'ID del video.");
        return;
    }

    router.post("/playlists/add", {
        playlist_id: playlistId.value,
        video_id: manualVideoId.value,
    });
};
</script>
