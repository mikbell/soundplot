<template>
    <AppLayout title="Video Casuale da YouTube">
        <div class="p-6">
            <h1 class="mb-6 text-3xl font-bold">Guarda un Video Casuale</h1>

            <div v-if="error" class="text-red-500">
                {{ error }}
            </div>

            <div v-else-if="video && video.video_id" class="mt-6">
                <iframe
                    width="100%"
                    height="315"
                    :src="`https://www.youtube.com/embed/${video.video_id}?autoplay=1`"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>

            <div v-else class="mt-6">
                Premi il pulsante per ottenere un video casuale.
            </div>
        </div>

        <div class="flex items-center justify-center gap-2 mt-6">
            <PrimaryButton @click="getRandomVideo"
                >Ottieni Video Casuale</PrimaryButton
            >
            <SecondaryButton
                @click="toggleFavorite"
                v-if="video && video.video_id"
            >
                {{
                    isFavorite
                        ? "Rimuovi dai Preferiti"
                        : "Aggiungi ai Preferiti"
                }}
            </SecondaryButton>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const { props } = usePage();
const video = ref(props.video || null);
const error = ref(props.error || null);
const isFavorite = ref(false);

const getRandomVideo = () => {
    console.log("Fetching random video...");
    router.get(route("youtube.random"), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: (page) => {
            console.log("Random video response:", page.props.video);
            video.value = page.props.video || null;
            error.value = page.props.error || null;
            if (video.value) {
                checkIfFavorite();
            }
        },
    });
};

const checkIfFavorite = async () => {
    if (!video.value || !video.video_id) {
        console.warn("No valid video to check if favorite.");
        isFavorite.value = false;
        return;
    }

    try {
        console.log("Checking if video is favorite...");
        const response = await axios.get(route("favorites.index"));
        console.log("Favorites response:", response);
        isFavorite.value = response.data.some(
            (fav) => fav.video_id === video.value.video_id
        );
    } catch (error) {
        console.error("Errore nel controllare i preferiti:", error);
    }
};

const toggleFavorite = () => {
    if (!video.value || !video.video_id) {
        console.warn(
            "Video non valido per aggiungere/rimuovere dai preferiti."
        );
        return;
    }

    if (isFavorite.value) {
        console.log("Removing video from favorites...");
        axios
            .delete(route("favorites.destroy", video.value.video_id))
            .then((response) => {
                console.log("Risposta dal backend (rimozione):", response);
                if (response.status === 200) {
                    isFavorite.value = false;
                }
            })
            .catch((error) => {
                console.error("Errore nel rimuovere dai preferiti:", error);
            });
    } else {
        console.log("Adding video to favorites...");
        axios
            .post(route("favorites.store"), {
                video_id: video.value.video_id,
            })
            .then((response) => {
                console.log("Risposta dal backend (aggiunta):", response);
                if (response.status === 200) {
                    isFavorite.value = true;
                }
            })
            .catch((error) => {
                console.error("Errore nell'aggiungere ai preferiti:", error);
            });
    }
};
</script>

<style scoped>
iframe {
    border-radius: 8px;
}
</style>
