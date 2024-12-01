<template>
    <AppLayout title="I Miei Preferiti">
        <div class="p-6">
            <h1 class="mb-6 text-3xl font-bold">I Miei Preferiti</h1>

            <div
                v-if="favorites.length > 0"
                class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3"
            >
                <div
                    v-for="favorite in favorites"
                    :key="favorite.id"
                    class="p-4 border rounded"
                >
                    <iframe
                        width="100%"
                        height="315"
                        :src="`https://www.youtube.com/embed/${favorite.video_id}`"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                    <div class="mt-4">
                        <DangerButton
                            @click="removeFromFavorites(favorite.video_id)"
                        >
                            Rimuovi dai Preferiti
                        </DangerButton>
                    </div>
                </div>
            </div>

            <div v-else class="mt-6">
                Non hai ancora aggiunto nessuna traccia ai preferiti.
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";

const { props } = usePage();
const favorites = ref(
    props.favorites.map((fav) => ({
        id: fav.id,
        video_id: fav.video_id,
        title: fav.title,
        thumbnail: fav.thumbnail,
    }))
);

const removeFromFavorites = (videoId) => {
    router.delete(route("favorites.destroy", videoId), {
        onSuccess: () => {
            favorites.value = favorites.value.filter(
                (favorite) => favorite.video_id !== videoId
            );
        },
    });
};
</script>

<style scoped>
iframe {
    border-radius: 8px;
}
</style>
