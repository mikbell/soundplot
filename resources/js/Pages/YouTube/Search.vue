<template>
    <AppLayout title="Cerca Sample su YouTube">
        <div class="p-6">
            <h1 class="mb-6 text-3xl font-bold">Cerca Sample su YouTube</h1>

            <form @submit.prevent="searchYouTube" class="mb-6">
                <input
                    v-model="query"
                    type="text"
                    placeholder="Cerca sample..."
                    class="w-full p-2 mb-4 border"
                />
                <PrimaryButton type="submit">Cerca</PrimaryButton>
            </form>

            <div v-if="videos.length > 0" class="grid grid-cols-1 gap-4 mt-6 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="video in videos" :key="video.id.videoId" class="p-4 border rounded">
                    <h2 class="mb-2 text-xl font-semibold">{{ video.snippet.title }}</h2>
                    <iframe
                        width="100%"
                        height="315"
                        :src="`https://www.youtube.com/embed/${video.id.videoId}`"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>

            <div v-else-if="query" class="mt-6">Nessun risultato trovato per "{{ query }}"</div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

// Ottieni i dati passati dal controller tramite Inertia
const { props } = usePage();
const videos = ref(props.videos);
const query = ref(props.query);

// Funzione per la ricerca di YouTube
const searchYouTube = () => {
    // Utilizza il form di Inertia per fare la richiesta al backend
    const form = useForm({
        query: query.value,
    });

    form.get(route('youtube.search'), {
        preserveScroll: true,
    });
};
</script>

<style scoped>
iframe {
    border-radius: 8px;
}
</style>
