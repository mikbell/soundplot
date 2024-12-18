<template>
    <AppLayout title="Dashboard">
        <div class="container px-4 py-6 mx-auto">
            <!-- Titolo -->
            <h1 class="mb-6 text-3xl font-extrabold text-gray-800">
                Samplette Clone 🎶
            </h1>

            <!-- Barra di ricerca -->
            <div class="mb-6">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cerca per titolo o artista..."
                    class="w-full p-3 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400"
                />
            </div>

            <!-- Lista delle tracce -->
            <div v-if="isLoading" class="text-center text-gray-500">
                Caricamento...
            </div>
            <div v-else>
                <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        v-for="track in tracks"
                        :key="track.id"
                        class="flex items-center p-4 transition duration-200 bg-gray-100 border rounded-lg shadow-md cursor-pointer hover:bg-gray-200"
                        @click="playTrack(track.audio_url)"
                    >
                        <!-- Icona Play -->
                        <div class="flex-shrink-0 mr-4">
                            <svg
                                class="w-8 h-8 text-gray-700 hover:text-gray-900"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </div>

                        <!-- Informazioni sulla traccia -->
                        <div class="flex items-center flex-1">
                            <h2
                                class="mr-4 text-lg font-semibold text-gray-800"
                            >
                                {{ track.title }}
                            </h2>
                            <p class="text-sm text-gray-600">
                                {{ track.artist_name }}
                            </p>
                        </div>

                        <img
                            :src="track.album_image"
                            alt="Artwork"
                            class="h-16 ml-4"
                        />
                    </div>
                </div>
            </div>

            <div
                v-if="!tracks.length && !isLoading"
                class="text-center text-gray-500"
            >
                Nessuna traccia trovata.
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
import { ref, watch } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

// Props per le tracce iniziali
const props = defineProps({
    initialTracks: Array,
});

// Stato
const searchQuery = ref("");
const tracks = ref([...props.initialTracks]);
const isLoading = ref(false);

// Funzione per riprodurre una traccia
const playTrack = (trackUrl) => {
    const audioPlayer = document.querySelector("audio");
    if (audioPlayer) {
        audioPlayer.src = trackUrl;
        audioPlayer.play();
    }
};

// Funzione di ricerca
const fetchTracks = async (query) => {
    isLoading.value = true;
    try {
        const response = await axios.get("/api/tracks/search", {
            params: { query },
        });
        tracks.value = response.data;
    } catch (error) {
        console.error("Errore nella ricerca delle tracce:", error);
    } finally {
        isLoading.value = false;
    }
};

// Watcher per la ricerca
watch(searchQuery, (newQuery) => {
    if (newQuery.trim() === "") {
        tracks.value = [...props.initialTracks]; // Usa la prop in modo sicuro
    } else {
        fetchTracks(newQuery);
    }
});
</script>
