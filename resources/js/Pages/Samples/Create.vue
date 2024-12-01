<template>
    <AppLayout title="Crea un nuovo Campione">
        <div class="p-6">
            <h1 class="mb-4 text-3xl font-bold">Crea un nuovo Campione</h1>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium">Titolo</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full px-2 py-1 border rounded"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium"
                        >Descrizione</label
                    >
                    <textarea
                        v-model="form.description"
                        class="w-full px-2 py-1 border rounded"
                        required
                    ></textarea>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium"
                        >Immagine</label
                    >
                    <input
                        @change="handleImage"
                        type="file"
                        class="w-full px-2 py-1 border rounded"
                    />
                </div>
                <button type="submit" class="btn btn-primary">Salva</button>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";

// Utilizza useForm per creare il form reattivo
const form = useForm({
    title: "",
    description: "",
    image: null,
});

// Metodo per gestire la sottomissione del form
const submit = () => {
    form.post("/samples");
};

// Metodo per gestire il caricamento dell'immagine
const handleImage = (event) => {
    form.image = event.target.files[0];
};
</script>
