<template>
    <AppLayout title="La Mia Wishlist">
        <div class="p-6">
            <h1 class="mb-4 text-3xl font-bold">La Mia Wishlist</h1>

            <div v-if="samples.length > 0">
                <div v-for="sample in samples" :key="sample.id" class="p-4 mb-4 border rounded">
                    <h2 class="text-xl font-semibold">{{ sample.title }}</h2>
                    <img
                        v-if="sample.image"
                        :src="`/storage/${sample.image}`"
                        alt="Immagine del campione"
                        class="object-cover w-32 h-32 mt-2"
                    />
                    <p class="mt-2">{{ sample.description }}</p>
                    <Link :href="route('samples.show', sample.id)" class="mt-4 btn btn-primary">Visualizza Dettagli</Link>
                </div>
            </div>
            <div v-else>
                <p>Non hai ancora aggiunto nessun campione alla tua wishlist.</p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { defineProps } from "vue";

const props = defineProps({
    samples: Array,
});
</script>
