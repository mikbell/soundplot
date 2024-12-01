<template>
    <AppLayout title="Dettagli del Campione">
        <div class="p-6">
            <h1 class="text-3xl font-bold">{{ sample.title }}</h1>
            <img
                v-if="sample.image"
                :src="`/storage/${sample.image}`"
                alt="Immagine del campione"
                class="object-cover w-full h-auto mt-4"
            />
            <p class="mt-4">{{ sample.description }}</p>
            <Link :href="route('samples.index')" class="mt-6">
                Torna alla Lista dei Campioni
            </Link>

            <div class="mt-4">
                <PrimaryButton  @click="addToWishlist">
                    Aggiungi alla Wishlist
                </PrimaryButton>
                <SecondaryButton @click="removeFromWishlist">
                    Rimuovi dalla Wishlist
                </SecondaryButton>
            </div>

            <div class="flex justify-end mt-4">
                <Link :href="route('samples.edit', sample.id)" class="mr-2">
                    Modifica
                </Link>
                <button @click="deleteSample" class="text-red-600">
                    Elimina
                </button>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    sample: Object,
});

const { auth } = usePage().props;

// Variabile reattiva per gestire lo stato `inWishlist`
const inWishlist = ref(false);

// Funzione per aggiornare `inWishlist` basato su `wishlistSamples`
const updateInWishlist = () => {
    inWishlist.value = auth.user && auth.user.wishlistSamples
        ? auth.user.wishlistSamples.some(
              (wishlistSample) => wishlistSample.id === props.sample.id
          )
        : false;
};

// Imposta inizialmente `inWishlist` basato su `auth.user.wishlistSamples`
updateInWishlist();

// Usa `watch` per aggiornare `inWishlist` ogni volta che cambia `auth.user`
watch(() => auth.user?.wishlistSamples, () => {
    updateInWishlist();
});

// Funzione per aggiungere un campione alla wishlist
const addToWishlist = () => {
    router.post(
        route("wishlist.store", props.sample.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                inWishlist.value = true; // Aggiorna lo stato dopo l'aggiunta
                console.log("Campione aggiunto alla wishlist!");
            },
        }
    );
};

// Funzione per rimuovere un campione dalla wishlist
const removeFromWishlist = () => {
    router.delete(route("wishlist.destroy", props.sample.id), {
        preserveScroll: true,
        onSuccess: () => {
            inWishlist.value = false; // Aggiorna lo stato dopo la rimozione
            console.log("Campione rimosso dalla wishlist!");
        },
    });
};

// Funzione per eliminare un campione (opzionale)
const deleteSample = () => {
    if (confirm("Sei sicuro di voler eliminare questo campione?")) {
        router.delete(route("samples.destroy", props.sample.id));
    }
};
</script>
