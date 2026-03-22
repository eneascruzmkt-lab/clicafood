<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    restaurant: Object,
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(price);
};

const getImageUrl = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};
</script>

<template>
    <AppLayout title="Admin - Restaurante">
        <div class="max-w-4xl">
            <!-- Back link -->
            <Link href="/admin/restaurants"
                  class="inline-flex items-center gap-1.5 text-dark-400 hover:text-white text-sm mb-6 transition-colors">
                <Icon name="arrow-left" class="w-4 h-4" />
                <span>Voltar para Restaurantes</span>
            </Link>

            <!-- Restaurant info card -->
            <div class="card mb-6">
                <div class="flex items-start gap-4 mb-6">
                    <div v-if="restaurant.logo" class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 ring-1 ring-dark-700">
                        <img :src="getImageUrl(restaurant.logo)" :alt="restaurant.name" class="w-full h-full object-cover" />
                    </div>
                    <div v-else class="w-16 h-16 rounded-xl bg-dark-800 flex items-center justify-center flex-shrink-0 ring-1 ring-dark-700">
                        <Icon name="restaurant" class="w-8 h-8 text-dark-500" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-display font-bold text-white">{{ restaurant.name }}</h1>
                        <p class="text-dark-400 text-sm font-mono mt-0.5">/{{ restaurant.slug }}</p>
                        <p v-if="restaurant.description" class="text-dark-300 text-sm mt-1">{{ restaurant.description }}</p>
                    </div>
                </div>

                <!-- Stats grid -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-dark-800 rounded-lg p-4 text-center">
                        <div class="flex items-center justify-center mb-2">
                            <Icon name="menu" class="w-5 h-5 text-brand-400" />
                        </div>
                        <p class="text-2xl font-bold text-white">{{ restaurant.menu_items_count }}</p>
                        <p class="text-xs text-dark-400 mt-0.5">Itens</p>
                    </div>
                    <div class="bg-dark-800 rounded-lg p-4 text-center">
                        <div class="flex items-center justify-center mb-2">
                            <Icon name="tag" class="w-5 h-5 text-brand-400" />
                        </div>
                        <p class="text-2xl font-bold text-white">{{ restaurant.categories_count }}</p>
                        <p class="text-xs text-dark-400 mt-0.5">Categorias</p>
                    </div>
                    <div class="bg-dark-800 rounded-lg p-4 text-center">
                        <div class="flex items-center justify-center mb-2">
                            <Icon name="chart" class="w-5 h-5 text-brand-400" />
                        </div>
                        <p class="text-2xl font-bold text-white">{{ restaurant.analytics_count }}</p>
                        <p class="text-xs text-dark-400 mt-0.5">Eventos</p>
                    </div>
                </div>
            </div>

            <!-- Owner info card -->
            <div class="card mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <Icon name="user" class="w-5 h-5 text-brand-400" />
                    <h3 class="font-bold text-white">Dono</h3>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm">
                        <Icon name="user" class="w-4 h-4 text-dark-500 flex-shrink-0" />
                        <span class="text-dark-300">{{ restaurant.user?.name }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <Icon name="globe" class="w-4 h-4 text-dark-500 flex-shrink-0" />
                        <span class="text-dark-300">{{ restaurant.user?.email }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <Icon name="phone" class="w-4 h-4 text-dark-500 flex-shrink-0" />
                        <span class="text-dark-300">{{ restaurant.user?.phone || 'Nao informado' }}</span>
                    </div>
                </div>
            </div>

            <!-- Menu items table -->
            <div class="card">
                <div class="flex items-center gap-2 mb-4">
                    <Icon name="menu" class="w-5 h-5 text-brand-400" />
                    <h3 class="font-bold text-white">Itens do Cardapio</h3>
                    <span class="ml-auto text-xs text-dark-400 bg-dark-800 px-2 py-0.5 rounded-full">
                        {{ restaurant.menu_items?.length || 0 }} itens
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-dark-400 border-b border-dark-700">
                                <th class="pb-3 font-medium">Item</th>
                                <th class="pb-3 font-medium">Categoria</th>
                                <th class="pb-3 font-medium">Preco</th>
                                <th class="pb-3 font-medium text-center">Video</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in restaurant.menu_items" :key="item.id"
                                class="border-b border-dark-800 last:border-0">
                                <td class="py-3">
                                    <div class="flex items-center gap-3">
                                        <div v-if="item.image"
                                             class="w-8 h-8 rounded-md overflow-hidden flex-shrink-0">
                                            <img :src="getImageUrl(item.image)" :alt="item.name"
                                                 class="w-full h-full object-cover" />
                                        </div>
                                        <div v-else class="w-8 h-8 rounded-md bg-dark-800 flex items-center justify-center flex-shrink-0">
                                            <Icon name="image" class="w-4 h-4 text-dark-600" />
                                        </div>
                                        <span class="text-white font-medium">{{ item.name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-dark-400">{{ item.category?.name || '-' }}</td>
                                <td class="py-3 text-dark-300">{{ formatPrice(item.price) }}</td>
                                <td class="py-3 text-center">
                                    <span v-if="item.video_url"
                                          class="inline-flex items-center gap-1 text-brand-400 text-xs">
                                        <Icon name="video" class="w-3.5 h-3.5" />
                                        Sim
                                    </span>
                                    <span v-else class="text-dark-600 text-xs">-</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p v-if="!restaurant.menu_items?.length"
                   class="text-dark-400 text-sm py-6 text-center">
                    Nenhum item cadastrado
                </p>
            </div>
        </div>
    </AppLayout>
</template>
