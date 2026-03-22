<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    items: Array,
});

const deleteItem = (id) => {
    if (confirm('Tem certeza que deseja excluir este item?')) {
        router.delete(`/menu-items/${id}`);
    }
};

const toggleAvailable = (item) => {
    router.put(`/menu-items/${item.id}`, {
        ...item,
        available: !item.available,
        category_id: item.category_id,
    }, { preserveScroll: true });
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(price);
};
</script>

<template>
    <AppLayout title="Cardápio">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl lg:text-3xl font-display font-bold text-white">Cardápio</h1>
                <p class="text-dark-400 mt-1">Gerencie os itens do seu cardápio</p>
            </div>
            <Link href="/menu-items/create" class="btn-primary">
                + Novo Item
            </Link>
        </div>

        <div v-if="items.length === 0" class="card text-center py-12">
            <p class="text-dark-400 text-lg mb-2">Seu cardápio está vazio</p>
            <p class="text-dark-500 text-sm mb-4">Adicione seu primeiro prato com foto e vídeo!</p>
            <Link href="/menu-items/create" class="btn-primary">
                Adicionar primeiro item
            </Link>
        </div>

        <div v-else class="space-y-3">
            <div v-for="item in items" :key="item.id"
                 class="card flex items-center gap-4 hover:border-dark-600 transition-colors">
                <!-- Image/Video indicator -->
                <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-dark-700 relative">
                    <img v-if="item.image" :src="'/storage/' + item.image" :alt="item.name"
                         class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center text-dark-500 text-xl">
                        🍽️
                    </div>
                    <!-- Video badge -->
                    <div v-if="item.video_url"
                         class="absolute bottom-0.5 right-0.5 bg-brand-500 rounded-full w-5 h-5 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-white truncate">{{ item.name }}</h3>
                        <span v-if="item.featured"
                              class="px-2 py-0.5 bg-yellow-500/20 text-yellow-400 text-xs rounded-full">
                            Destaque
                        </span>
                    </div>
                    <p class="text-sm text-dark-400 truncate">{{ item.category?.name }}</p>
                    <p class="text-sm font-semibold text-brand-400">{{ formatPrice(item.price) }}</p>
                </div>

                <!-- Stats -->
                <div class="hidden sm:flex items-center gap-4 text-xs text-dark-400">
                    <span>{{ item.views_count }} views</span>
                    <span v-if="item.video_url">{{ item.video_plays_count }} plays</span>
                </div>

                <!-- Status -->
                <button @click="toggleAvailable(item)"
                        :class="item.available ? 'bg-green-500/20 text-green-400' : 'bg-dark-700 text-dark-400'"
                        class="px-3 py-1 rounded-full text-xs font-medium transition-colors hidden sm:block">
                    {{ item.available ? 'Disponível' : 'Indisponível' }}
                </button>

                <!-- Actions -->
                <div class="flex items-center gap-1">
                    <Link :href="`/menu-items/${item.id}/edit`"
                          class="text-dark-400 hover:text-white transition-colors p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </Link>
                    <button @click="deleteItem(item.id)"
                            class="text-dark-400 hover:text-red-400 transition-colors p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
