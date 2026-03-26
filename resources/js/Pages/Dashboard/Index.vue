<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    stats: Object,
    topItems: Array,
    topVideos: Array,
    recentViews: Array,
    restaurant: Object,
});

const mediaUrl = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};

const statCards = computed(() => [
    { label: 'Visualizacoes', value: props.stats.totalViews, icon: 'eye', color: 'text-brand-400', bg: 'bg-brand-500/10' },
    { label: 'Cliques', value: props.stats.totalClicks, icon: 'chart', color: 'text-amber-400', bg: 'bg-amber-500/10' },
    { label: 'Videos assistidos', value: props.stats.totalVideoPlays, icon: 'play', color: 'text-emerald-400', bg: 'bg-emerald-500/10' },
    { label: 'Itens no cardapio', value: props.stats.totalItems, icon: 'restaurant', color: 'text-dark-300', bg: 'bg-dark-700/60' },
]);
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-display font-bold text-white">Dashboard</h1>
            <p class="text-dark-400 mt-1">Visao geral do seu cardapio digital</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div
                v-for="(stat, index) in statCards"
                :key="stat.label"
                class="stat-card"
                :class="`animation-delay-${(index + 1) * 100}`"
            >
                <div class="flex items-center justify-between mb-3">
                    <div :class="[stat.color, stat.bg, 'rounded-lg p-2']">
                        <Icon :name="stat.icon" class="w-5 h-5" />
                    </div>
                </div>
                <p class="text-2xl lg:text-3xl font-bold text-white">
                    {{ stat.value.toLocaleString('pt-BR') }}
                </p>
                <p class="text-sm text-dark-400 mt-1">{{ stat.label }}</p>
            </div>
        </div>

        <!-- Top Items & Top Videos -->
        <div class="grid lg:grid-cols-2 gap-6">
            <!-- Top Items -->
            <div class="card">
                <h3 class="text-lg font-bold text-white mb-4">Itens mais vistos</h3>
                <div v-if="topItems.length === 0" class="text-dark-400 text-sm py-4 text-center">
                    Nenhum dado ainda. Adicione itens ao cardapio.
                </div>
                <div v-else class="space-y-3">
                    <div
                        v-for="(item, index) in topItems"
                        :key="item.id"
                        class="flex items-center gap-3 py-2 border-b border-dark-700 last:border-0"
                    >
                        <span class="text-sm font-bold text-dark-400 w-6">{{ index + 1 }}</span>
                        <div v-if="item.image" class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0">
                            <img
                                :src="mediaUrl(item.image)"
                                :alt="item.name"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ item.name }}</p>
                            <p class="text-xs text-dark-400">
                                {{ item.views_count.toLocaleString('pt-BR') }} visualizacoes
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Videos -->
            <div class="card">
                <h3 class="text-lg font-bold text-white mb-4">Videos mais assistidos</h3>
                <div v-if="topVideos.length === 0" class="text-dark-400 text-sm py-4 text-center">
                    Nenhum video ainda. Adicione videos aos seus pratos.
                </div>
                <div v-else class="space-y-3">
                    <div
                        v-for="(item, index) in topVideos"
                        :key="item.id"
                        class="flex items-center gap-3 py-2 border-b border-dark-700 last:border-0"
                    >
                        <span class="text-sm font-bold text-dark-400 w-6">{{ index + 1 }}</span>
                        <div class="text-brand-400 bg-dark-800 rounded-lg p-2 flex-shrink-0">
                            <Icon name="play" class="w-4 h-4" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ item.name }}</p>
                            <p class="text-xs text-dark-400">
                                {{ item.video_plays_count.toLocaleString('pt-BR') }} reproducoes
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Public Menu Link -->
        <div class="mt-8 card bg-gradient-to-r from-brand-500/10 to-brand-600/5 border-brand-500/20">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <Icon name="globe" class="w-5 h-5 text-brand-400" />
                        Seu cardapio publico
                    </h3>
                    <p class="text-dark-300 text-sm mt-1">
                        Compartilhe este link com seus clientes:
                        <a
                            :href="'/' + restaurant.slug"
                            target="_blank"
                            class="text-brand-400 hover:text-brand-300 underline"
                        >
                            {{ restaurant.slug }}
                        </a>
                    </p>
                </div>
                <a :href="'/' + restaurant.slug" target="_blank" class="btn-primary text-sm">
                    <Icon name="external-link" class="w-4 h-4" />
                    Ver cardapio
                </a>
            </div>
        </div>
    </AppLayout>
</template>
