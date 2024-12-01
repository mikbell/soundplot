<template>
    <AppLayout title="Elenco dei Campioni">
        <div class="p-6">
            <h1 class="text-3xl font-bold">Elenco dei Campioni</h1>
            <div
                v-for="sample in samples"
                :key="sample.id"
                class="p-4 mt-4 border rounded"
            >
            <Link :href="route('samples.show', sample.id)">
                <h2 class="text-xl font-semibold">{{ sample.title }}</h2>
                <p>{{ sample.description }}</p>

            </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    samples: Array,
})
</script>
