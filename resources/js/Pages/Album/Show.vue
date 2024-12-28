<template>
    <AppLayout title="Dashboard">
        <div class="container px-4 py-6 mx-auto">
            <!-- Titolo dell'album -->
            <h1 class="mb-6 text-3xl font-extrabold text-center text-gray-800">
                {{ album.name }}
            </h1>

            <div class="flex items-center justify-center mb-6">
                <img :src="album.image" alt="Album cover" class="w-96 h-96" />
            </div>

            <!-- Lista delle tracce -->
            <div v-if="album.tracks && album.tracks.length > 0">
                <div class="max-w-3xl mx-auto space-y-2">
                    <div
                        v-for="track in album.tracks"
                        :key="track.id"
                        class="flex items-center p-4 transition duration-200 bg-gray-100 border rounded-lg shadow-md cursor-pointer hover:bg-gray-200"
                        @click="toggleTrack(track)"
                    >
                        <div class="flex-shrink-0 mr-4">
                            <!-- Icona Play o Pausa -->
                            <svg
                                v-if="
                                    currentTrack &&
                                    currentTrack.id === track.id &&
                                    isPlaying
                                "
                                class="w-8 h-8 text-gray-700 hover:text-gray-900"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                            </svg>
                            <svg
                                v-else
                                class="w-8 h-8 text-gray-700 hover:text-gray-900"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <!-- Nome traccia -->
                            <h2
                                class="text-lg font-semibold text-gray-800 truncate"
                            >
                                {{ track.name }}
                            </h2>
                        </div>
                        <!-- Pulsante Scarica -->
                        <a
                            v-if="track.audiodownload_allowed"
                            :href="track.audiodownload"
                            download
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"
                                />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div v-else class="text-center text-gray-500">
                Nessuna traccia disponibile per questo album.
            </div>

            <!-- Player Audio -->
            <div class="flex justify-center mt-8">
                <audio
                    ref="audioPlayer"
                    controls
                    class="w-full max-w-3xl border border-gray-300 rounded-lg shadow-lg"
                ></audio>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

// Props per l'album e le tracce
const props = defineProps({
    album: Array,
});

// Stato per la traccia in riproduzione
const currentTrack = ref(null);
const isPlaying = ref(false);

// Funzione per gestire la riproduzione
const toggleTrack = (track) => {
    const audioPlayer = document.querySelector("audio");

    if (currentTrack.value && currentTrack.value.id === track.id) {
        // Pausa se è già in riproduzione
        if (isPlaying.value) {
            audioPlayer.pause();
            isPlaying.value = false;
        } else {
            audioPlayer.play();
            isPlaying.value = true;
        }
    } else {
        // Riproduzione di una nuova traccia
        currentTrack.value = track;
        isPlaying.value = true;
        audioPlayer.src = track.audio;
        audioPlayer.play();
    }
};

// Gestione eventi del player audio
const audioPlayer = document.querySelector("audio");
audioPlayer?.addEventListener("ended", () => {
    isPlaying.value = false; // Reset dello stato quando la traccia termina
});
</script>
