<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

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
    <AppLayout title="Cardapio">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl lg:text-3xl font-display font-bold text-white">Cardapio</h1>
                <p class="text-dark-400 mt-1">Gerencie os itens do seu cardapio</p>
            </div>
            <Link href="/menu-items/create" class="btn-primary">
                <Icon name="plus" class="w-4 h-4" />
                Novo Item
            </Link>
        </div>

        <!-- Empty State -->
        <div v-if="items.length === 0" class="card text-center py-12">
            <div class="flex justify-center mb-4">
                <div class="bg-dark-800 rounded-full p-4">
                    <Icon name="restaurant" class="w-10 h-10 text-dark-500" />
                </div>
            </div>
            <p class="text-dark-400 text-lg mb-2">Seu cardapio esta vazio</p>
            <p class="text-dark-500 text-sm mb-4">Adicione seu primeiro prato com foto e video.</p>
            <Link href="/menu-items/create" class="btn-primary">
                <Icon name="plus" class="w-4 h-4" />
                Adicionar primeiro item
            </Link>
        </div>

        <!-- Items List -->
        <div v-else class="space-y-3">
            <div
                v-for="item in items"
                :key="item.id"
                class="card flex items-center gap-4 hover:border-dark-600 transition-colors"
            >
                <!-- Image / Video indicator -->
                <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-dark-700 relative">
                    <img
                        v-if="item.image"
                        :src="'/storage/' + item.image"
                        :alt="item.name"
                        class="w-full h-full object-cover"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center">
                        <Icon name="image" class="w-6 h-6 text-dark-500" />
                    </div>
                    <!-- Video badge -->
                    <div
                        v-if="item.video_url"
                        class="absolute bottom-0.5 right-0.5 bg-brand-500 rounded-full w-5 h-5 flex items-center justify-center"
                    >
                        <Icon name="play" class="w-3 h-3 text-white" />
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-white truncate">{{ item.name }}</h3>
                        <span
                            v-if="item.featured"
                            class="inline-flex items-center gap-1 px-2 py-0.5 bg-yellow-500/20 text-yellow-400 text-xs rounded-full"
                        >
                            <Icon name="star" class="w-3 h-3" />
                            Destaque
                        </span>
                    </div>
                    <p class="text-sm text-dark-400 truncate">{{ item.category?.name }}</p>
                    <p class="text-sm font-semibold text-brand-400">{{ formatPrice(item.price) }}</p>
                </div>

                <!-- Stats -->
                <div class="hidden sm:flex items-center gap-4 text-xs text-dark-400">
                    <span class="flex items-center gap-1">
                        <Icon name="eye" class="w-3.5 h-3.5" />
                        {{ item.views_count }}
                    </span>
                    <span v-if="item.video_url" class="flex items-center gap-1">
                        <Icon name="play" class="w-3.5 h-3.5" />
                        {{ item.video_plays_count }}
                    </span>
                </div>

                <!-- Status Toggle -->
                <button
                    @click="toggleAvailable(item)"
                    :class="item.available ? 'bg-green-500/20 text-green-400' : 'bg-dark-700 text-dark-400'"
                    class="px-3 py-1 rounded-full text-xs font-medium transition-colors hidden sm:block"
                >
                    {{ item.available ? 'Disponivel' : 'Indisponivel' }}
                </button>

                <!-- Actions -->
                <div class="flex items-center gap-1">
                    <Link
                        :href="`/menu-items/${item.id}/edit`"
                        class="text-dark-400 hover:text-white transition-colors p-2"
                    >
                        <Icon name="edit" class="w-5 h-5" />
                    </Link>
                    <button
                        @click="deleteItem(item.id)"
                        class="text-dark-400 hover:text-red-400 transition-colors p-2"
                    >
                        <Icon name="trash" class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
