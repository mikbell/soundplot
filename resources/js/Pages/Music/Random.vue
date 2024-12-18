<template>
    <AppLayout :title="track.title">
        <div class="container px-6 py-10 mx-auto">
            <!-- Titolo del brano -->
            <h1 class="mb-8 text-5xl font-bold text-center text-gray-800">
                {{ track.title }}
            </h1>

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
                <img
                    :src="track.album_image"
                    alt="Artwork"
                    class="mb-6 border border-gray-300 rounded-lg shadow-xl w-72 h-72"
                />

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
                <p class="text-lg font-semibold">
                    <span class="text-indigo-500">Artista:</span>
                    {{ track.artist_name }}
                </p>
                <p class="text-lg font-semibold">
                    <span class="text-indigo-500">Traccia:</span>
                    {{ track.title }}
                </p>
                <p class="text-lg font-semibold">
                    <span class="text-indigo-500">Album:</span>
                    {{ track.album_name }}
                </p>
                <p class="text-lg font-semibold">
                    <span class="text-indigo-500">Data di Rilascio:</span>
                    {{ formatDate(track.release_date) }}
                </p>
                <Link
                    :href="track.audiodownload"
                    class="inline-block mt-4 text-indigo-500 underline hover:text-indigo-700"
                >
                    Download
                </Link>
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
    console.log("Fetching random track for genre:", selectedGenre.value);
    router.get(route("music.random"), {
        genre: selectedGenre.value,
        preserveScroll: true,
        onSuccess: (page) => {
            console.log("Random track response:", page.props.track);
            if (page.props.track && page.props.track.track_id) {
                track.value = page.props.track;
                console.log("Track value after response:", track.value);
                checkIfFavorite();
            } else {
                console.warn("No valid track received from backend.");
                track.value = null;
            }
            error.value = page.props.error || "";
            selectedGenre.value = page.props.selectedGenre || "";
        },
    });
};
</script>
