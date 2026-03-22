<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    viewsByDay: Array,
    topItems: Array,
    topVideos: Array,
    stats: Object,
    period: String,
});

const currentPeriod = ref(props.period);

const changePeriod = (p) => {
    currentPeriod.value = p;
    router.get('/analytics', { period: p }, { preserveState: true });
};

const periods = [
    { key: 'today', label: 'Hoje' },
    { key: 'week', label: '7 dias' },
    { key: 'month', label: '30 dias' },
    { key: 'year', label: 'Ano' },
];

const maxViews = Math.max(...(props.viewsByDay?.map(d => d.count) || [1]), 1);
</script>

<template>
    <AppLayout title="Métricas">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-display font-bold text-white">Métricas</h1>
            <p class="text-dark-400 mt-1">Acompanhe o desempenho do seu cardápio</p>
        </div>

        <!-- Period selector -->
        <div class="flex gap-2 mb-6">
            <button v-for="p in periods" :key="p.key" @click="changePeriod(p.key)"
                    :class="currentPeriod === p.key ? 'bg-brand-500 text-white' : 'bg-dark-800 text-dark-300 hover:text-white'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                {{ p.label }}
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="card text-center">
                <p class="text-3xl font-bold text-white">{{ stats.totalViews.toLocaleString('pt-BR') }}</p>
                <p class="text-sm text-dark-400">Visualizações</p>
            </div>
            <div class="card text-center">
                <p class="text-3xl font-bold text-white">{{ stats.totalClicks.toLocaleString('pt-BR') }}</p>
                <p class="text-sm text-dark-400">Cliques</p>
            </div>
            <div class="card text-center">
                <p class="text-3xl font-bold text-white">{{ stats.totalVideoPlays.toLocaleString('pt-BR') }}</p>
                <p class="text-sm text-dark-400">Vídeos assistidos</p>
            </div>
        </div>

        <!-- Chart (simple bar chart) -->
        <div class="card mb-8">
            <h3 class="text-lg font-bold text-white mb-4">Visualizações por dia</h3>
            <div v-if="viewsByDay.length === 0" class="text-dark-400 text-sm py-8 text-center">
                Nenhum dado neste período
            </div>
            <div v-else class="flex items-end gap-1 h-40">
                <div v-for="day in viewsByDay" :key="day.date" class="flex-1 flex flex-col items-center gap-1">
                    <span class="text-xs text-dark-400">{{ day.count }}</span>
                    <div class="w-full bg-brand-500 rounded-t transition-all"
                         :style="{ height: (day.count / maxViews * 100) + '%', minHeight: '4px' }"></div>
                    <span class="text-[10px] text-dark-500 truncate w-full text-center">
                        {{ new Date(day.date).toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' }) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Rankings -->
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="card">
                <h3 class="text-lg font-bold text-white mb-4">Itens mais vistos</h3>
                <div v-for="(item, i) in topItems" :key="item.id"
                     class="flex items-center gap-3 py-2 border-b border-dark-700 last:border-0">
                    <span class="text-sm font-bold text-dark-500 w-6">{{ i + 1 }}</span>
                    <span class="flex-1 text-sm text-white">{{ item.name }}</span>
                    <span class="text-sm text-dark-400">{{ item.views_count }}</span>
                </div>
                <p v-if="topItems.length === 0" class="text-dark-400 text-sm text-center py-4">Sem dados</p>
            </div>

            <div class="card">
                <h3 class="text-lg font-bold text-white mb-4">Vídeos mais assistidos</h3>
                <div v-for="(item, i) in topVideos" :key="item.id"
                     class="flex items-center gap-3 py-2 border-b border-dark-700 last:border-0">
                    <span class="text-sm font-bold text-dark-500 w-6">{{ i + 1 }}</span>
                    <span class="flex-1 text-sm text-white">{{ item.name }}</span>
                    <span class="text-sm text-dark-400">{{ item.video_plays_count }} plays</span>
                </div>
                <p v-if="topVideos.length === 0" class="text-dark-400 text-sm text-center py-4">Sem dados</p>
            </div>
        </div>
    </AppLayout>
</template>
