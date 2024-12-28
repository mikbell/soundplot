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
            <div
                v-if="isLoading"
                class="text-center text-gray-500 animate-pulse"
            >
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
                        @click="togglePlayPause(track)"
                    >
                        <!-- Icona Play/Pause -->
                        <div class="flex-shrink-0 mr-4">
                            <svg
                                v-if="track.isPlaying"
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

                        <Link :href="route('album.show', track.album_id)">
                            <img
                                :src="track.album_image"
                                alt="Artwork"
                                class="h-16 ml-4"
                            />
                        </Link>
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
import { Link } from "@inertiajs/vue3";
import debounce from "lodash/debounce";

// Props per le tracce iniziali
const props = defineProps({
    initialTracks: Array,
});

// Stato
const searchQuery = ref("");
const tracks = ref(
    props.initialTracks.map((track) => ({
        ...track,
        isPlaying: false,
    }))
);
const isLoading = ref(false);
const currentTrackId = ref(null); // Tiene traccia della traccia in riproduzione

const togglePlayPause = (track) => {
    const audioPlayer = document.querySelector("audio");

    if (track.isPlaying) {
        // Pausa
        audioPlayer.pause();
        track.isPlaying = false;
    } else {
        // Ferma tutte le tracce in riproduzione
        tracks.value.forEach((t) => (t.isPlaying = false));

        // Riproduzione
        audioPlayer.src = track.audio_url;
        audioPlayer.play();
        track.isPlaying = true;
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

watch(
    searchQuery,
    debounce((newQuery) => {
        if (newQuery.trim() === "") {
            tracks.value = [...props.initialTracks];
        } else {
            fetchTracks(newQuery);
        }
    }, 300)
);
</script>

<style scoped>
svg {
    transition: transform 0.2s ease-in-out;
}
svg:hover {
    transform: scale(1.2);
}
</style>
