<template>
    <AppLayout :title="track.title">
        <div class="container px-6 py-10 mx-auto">
            <!-- Messaggio di errore -->
            <div
                v-if="error"
                class="mb-6 text-xl font-semibold text-center text-red-500"
            >
                {{ error }}
            </div>

            <!-- Contenuto principale -->
            <div
                v-else-if="track && track.track_id"
                class="flex flex-col items-center"
            >
                <!-- Immagine dell'album -->
                <Link :href="route('album.show', track.album_id)"
                    ><img
                        :src="track.album_image"
                        alt="Artwork"
                        class="mb-6 border border-gray-300 rounded-lg shadow-xl w-96 h-96"
                    />
                </Link>

                <!-- Player audio -->
                <audio
                    class="w-full max-w-3xl border border-gray-300 rounded-lg shadow-lg"
                    :src="track.audio_url"
                    controls
                    autoplay
                ></audio>

                <!-- Selezione genere e pulsante casuale -->
                <div class="flex items-center justify-center gap-4 mt-4">
                    <SelectInput v-model="selectedGenre">
                        <option value="">Tutti i generi</option>
                        <option
                            v-for="genre in genres"
                            :key="genre"
                            :value="genre"
                        >
                            {{ genre }}
                        </option>
                    </SelectInput>
                    <PrimaryButton @click="getRandomTrack">
                        Brano Casuale
                    </PrimaryButton>

                    <a
                        v-if="track.audiodownload"
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

            <!-- Nessun brano trovato -->
            <div
                v-else
                class="mt-6 text-xl font-semibold text-center text-gray-700"
            >
                Premi il pulsante per ottenere un brano.
            </div>

            <!-- Informazioni sul brano -->
            <div
                class="p-6 mt-8 text-gray-800 bg-gray-100 rounded-lg shadow-lg"
            >
                <p class="font-semibold">
                    <span class="text-blue-500">Artista: </span>
                    {{ track.artist_name }}
                </p>
                <p class="font-semibold">
                    <span class="text-blue-500">Traccia: </span>
                    {{ track.title }}
                </p>
                <p class="font-semibold">
                    <span class="text-blue-500">Album: </span>
                    <Link :href="route('album.show', track.album_id)">{{
                        track.album_name
                    }}</Link>
                </p>
                <p class="font-semibold">
                    <span class="text-blue-500">Data di Rilascio: </span>
                    {{ formatDate(track.release_date) }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { router } from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";
import dayjs from "dayjs";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const formatDate = (date) => {
    return dayjs(date).format("DD/MM/YYYY");
};

const props = defineProps({
    tracks: {
        type: Array,
        default: () => [],
    },
    track: {
        type: Object,
        default: () => null,
    },
    error: {
        type: String,
        default: "",
    },
    selectedGenre: {
        type: String,
        default: "",
    },
});

const track = ref(props.track);
const currentTrackIndex = ref(0); // Indice della traccia corrente
const playlist = ref(props.tracks || []); // Playlist con tutte le tracce
const error = ref(props.error);
const selectedGenre = ref(props.selectedGenre);
const genres = [
    "pop",
    "rock",
    "metal",
    "rap",
    "country",
    "blues",
    "reggae",
    "latina",
    "rnb",
    "jazz",
    "electronic",
    "indie",
    "lofi",
    "classical",
    "hiphop",
    "ambient",
];

const getRandomTrack = () => {
    router.get(route("music.random"), {
        genre: selectedGenre.value,
        preserveScroll: true,
        onSuccess: (page) => {
            if (page.props.track && page.props.track.track_id) {
                track.value = page.props.track;
                // Aggiungi la traccia casuale alla playlist
                playlist.value.push(page.props.track);
                currentTrackIndex.value = playlist.value.length - 1;
            } else {
                console.warn("No valid track received from backend.");
                track.value = null;
            }
            error.value = page.props.error || "";
            selectedGenre.value = page.props.selectedGenre || "";
        },
    });
};

const playPreviousTrack = () => {
    if (currentTrackIndex.value > 0) {
        currentTrackIndex.value--;
        track.value = playlist.value[currentTrackIndex.value];
    } else {
        console.log("Siamo già alla prima traccia.");
    }
};
</script>
