<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    title: String,
});
</script>

<template>
    <div class="min-h-screen bg-dark-950 flex items-center justify-center p-4 relative overflow-hidden">
        <Head :title="title" />

        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Ctext x=\'50%25\' y=\'50%25\' font-size=\'20\' fill=\'%23ffffff\' text-anchor=\'middle\' dominant-baseline=\'middle\' font-family=\'sans-serif\' font-weight=\'bold\'%3ECF%3C/text%3E%3C/svg%3E'); background-repeat: repeat;">
        </div>

        <div class="w-full max-w-md relative z-10">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <h1 class="text-4xl font-display font-black">
                        <span class="text-brand-500">Clica</span><span class="text-white">Food</span>
                    </h1>
                </a>
                <p class="text-dark-400 mt-2 text-sm">Cardápio digital com vídeo</p>
            </div>

            <!-- Card -->
            <div class="bg-dark-900 border border-dark-700 rounded-2xl p-8 shadow-2xl">
                <slot />
            </div>
        </div>
    </div>
</template>
