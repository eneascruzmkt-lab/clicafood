<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    restaurant: Object,
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(price);
};
</script>

<template>
    <AppLayout title="Admin - Restaurante">
        <div class="max-w-3xl">
            <Link href="/admin/restaurants" class="text-dark-400 hover:text-white text-sm mb-4 inline-block">
                ← Voltar
            </Link>

            <div class="card mb-6">
                <div class="flex items-center gap-4 mb-4">
                    <div v-if="restaurant.logo" class="w-16 h-16 rounded-xl overflow-hidden">
                        <img :src="'/storage/' + restaurant.logo" class="w-full h-full object-cover" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ restaurant.name }}</h1>
                        <p class="text-dark-400 text-sm font-mono">/{{ restaurant.slug }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-dark-800 rounded-lg p-3">
                        <p class="text-xl font-bold text-white">{{ restaurant.menu_items_count }}</p>
                        <p class="text-xs text-dark-400">Itens</p>
                    </div>
                    <div class="bg-dark-800 rounded-lg p-3">
                        <p class="text-xl font-bold text-white">{{ restaurant.categories_count }}</p>
                        <p class="text-xs text-dark-400">Categorias</p>
                    </div>
                    <div class="bg-dark-800 rounded-lg p-3">
                        <p class="text-xl font-bold text-white">{{ restaurant.analytics_count }}</p>
                        <p class="text-xs text-dark-400">Eventos</p>
                    </div>
                </div>
            </div>

            <div class="card mb-6">
                <h3 class="font-bold text-white mb-3">Dono</h3>
                <p class="text-sm text-dark-300">{{ restaurant.user?.name }} — {{ restaurant.user?.email }}</p>
                <p class="text-sm text-dark-400">Tel: {{ restaurant.user?.phone || '-' }}</p>
            </div>

            <div class="card">
                <h3 class="font-bold text-white mb-3">Itens do Cardápio</h3>
                <div v-for="item in restaurant.menu_items" :key="item.id"
                     class="flex items-center justify-between py-2 border-b border-dark-700 last:border-0 text-sm">
                    <span class="text-white">{{ item.name }}</span>
                    <div class="flex items-center gap-4 text-dark-400">
                        <span v-if="item.video_url" class="text-brand-400 text-xs">Video</span>
                        <span>{{ formatPrice(item.price) }}</span>
                    </div>
                </div>
                <p v-if="!restaurant.menu_items?.length" class="text-dark-400 text-sm py-4 text-center">Sem itens</p>
            </div>
        </div>
    </AppLayout>
</template>
