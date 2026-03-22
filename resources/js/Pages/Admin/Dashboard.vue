<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    stats: Object,
    recentRestaurants: Array,
});
</script>

<template>
    <AppLayout title="Admin - Dashboard">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-display font-bold text-white">Admin Panel</h1>
            <p class="text-dark-400 mt-1">Visao geral da plataforma</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stat-card card text-center">
                <div class="flex items-center justify-center mb-2">
                    <Icon name="users" class="w-6 h-6 text-brand-400" />
                </div>
                <p class="text-3xl font-bold text-white">{{ stats.totalUsers }}</p>
                <p class="text-sm text-dark-400 mt-1">Usuarios</p>
            </div>
            <div class="stat-card card text-center">
                <div class="flex items-center justify-center mb-2">
                    <Icon name="restaurant" class="w-6 h-6 text-brand-400" />
                </div>
                <p class="text-3xl font-bold text-white">{{ stats.totalRestaurants }}</p>
                <p class="text-sm text-dark-400 mt-1">Restaurantes</p>
            </div>
            <div class="stat-card card text-center">
                <div class="flex items-center justify-center mb-2">
                    <Icon name="eye" class="w-6 h-6 text-brand-400" />
                </div>
                <p class="text-3xl font-bold text-white">{{ stats.totalViews.toLocaleString('pt-BR') }}</p>
                <p class="text-sm text-dark-400 mt-1">Visualizacoes totais</p>
            </div>
            <div class="stat-card card text-center">
                <div class="flex items-center justify-center mb-2">
                    <Icon name="dollar" class="w-6 h-6 text-brand-400" />
                </div>
                <p class="text-3xl font-bold text-brand-500">{{ stats.activePlans }}</p>
                <p class="text-sm text-dark-400 mt-1">Planos Pro ativos</p>
            </div>
        </div>

        <!-- Recent -->
        <div class="card">
            <h3 class="text-lg font-bold text-white mb-4">Restaurantes recentes</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-dark-400 border-b border-dark-700">
                            <th class="pb-3 font-medium">Restaurante</th>
                            <th class="pb-3 font-medium">Dono</th>
                            <th class="pb-3 font-medium">Slug</th>
                            <th class="pb-3 font-medium">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="r in recentRestaurants" :key="r.id"
                            class="border-b border-dark-800 last:border-0">
                            <td class="py-3 text-white font-medium">{{ r.name }}</td>
                            <td class="py-3 text-dark-300">{{ r.user?.name }} ({{ r.user?.email }})</td>
                            <td class="py-3 text-dark-400 font-mono text-xs">{{ r.slug }}</td>
                            <td class="py-3 text-dark-400">
                                {{ new Date(r.created_at).toLocaleDateString('pt-BR') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p v-if="recentRestaurants.length === 0" class="text-dark-400 text-sm text-center py-4">
                    Nenhum restaurante cadastrado
                </p>
            </div>
        </div>
    </AppLayout>
</template>
