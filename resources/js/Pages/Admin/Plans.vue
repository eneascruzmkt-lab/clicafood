<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    subscriptions: Object,
});

const updateStatus = (sub, status) => {
    router.put(`/admin/plans/${sub.id}`, {
        plan: sub.plan,
        status: status,
        ends_at: status === 'cancelled' ? new Date().toISOString() : sub.ends_at,
    });
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('pt-BR');
};

const statusLabel = (status) => {
    const labels = {
        active: 'Ativo',
        cancelled: 'Cancelado',
        expired: 'Expirado',
    };
    return labels[status] || status;
};

const statusClasses = (status) => {
    switch (status) {
        case 'active':
            return 'bg-green-500/10 text-green-400 ring-1 ring-green-500/20';
        case 'cancelled':
            return 'bg-red-500/10 text-red-400 ring-1 ring-red-500/20';
        case 'expired':
            return 'bg-yellow-500/10 text-yellow-400 ring-1 ring-yellow-500/20';
        default:
            return 'bg-dark-700 text-dark-400';
    }
};
</script>

<template>
    <AppLayout title="Admin - Planos">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <Icon name="dollar" class="w-7 h-7 text-brand-400" />
                <h1 class="text-2xl font-display font-bold text-white">Gestao de Planos</h1>
            </div>
            <span class="text-sm text-dark-400 bg-dark-800 px-3 py-1 rounded-full">
                {{ subscriptions.data?.length || 0 }} assinaturas
            </span>
        </div>

        <div class="card overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-dark-400 border-b border-dark-700">
                        <th class="pb-3 font-medium">Usuario</th>
                        <th class="pb-3 font-medium">Plano</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium">Inicio</th>
                        <th class="pb-3 font-medium">Fim</th>
                        <th class="pb-3 font-medium">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="sub in subscriptions.data" :key="sub.id"
                        class="border-b border-dark-800 last:border-0">
                        <!-- User -->
                        <td class="py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-dark-800 flex items-center justify-center flex-shrink-0 ring-1 ring-dark-700">
                                    <Icon name="user" class="w-4 h-4 text-dark-500" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-white font-medium truncate">{{ sub.user?.name }}</p>
                                    <p class="text-xs text-dark-400 truncate">{{ sub.user?.email }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Plan badge -->
                        <td class="py-3">
                            <span :class="sub.plan === 'pro'
                                    ? 'bg-brand-500/15 text-brand-400 ring-1 ring-brand-500/20'
                                    : 'bg-dark-700 text-dark-400 ring-1 ring-dark-600'"
                                  class="px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase">
                                {{ sub.plan }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="py-3">
                            <span :class="statusClasses(sub.status)"
                                  class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                <Icon v-if="sub.status === 'active'" name="check" class="w-3 h-3" />
                                <Icon v-else-if="sub.status === 'cancelled'" name="close" class="w-3 h-3" />
                                <Icon v-else name="clock" class="w-3 h-3" />
                                {{ statusLabel(sub.status) }}
                            </span>
                        </td>

                        <!-- Start date -->
                        <td class="py-3 text-dark-400 text-xs">
                            {{ formatDate(sub.starts_at) }}
                        </td>

                        <!-- End date -->
                        <td class="py-3 text-dark-400 text-xs">
                            {{ sub.ends_at ? formatDate(sub.ends_at) : 'Indefinido' }}
                        </td>

                        <!-- Actions -->
                        <td class="py-3">
                            <button v-if="sub.status === 'active'"
                                    @click="updateStatus(sub, 'cancelled')"
                                    class="inline-flex items-center gap-1 text-red-400 hover:text-red-300 text-xs transition-colors">
                                <Icon name="close" class="w-3.5 h-3.5" />
                                Cancelar
                            </button>
                            <button v-else
                                    @click="updateStatus(sub, 'active')"
                                    class="inline-flex items-center gap-1 text-green-400 hover:text-green-300 text-xs transition-colors">
                                <Icon name="check" class="w-3.5 h-3.5" />
                                Ativar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p v-if="subscriptions.data?.length === 0"
               class="text-dark-400 text-sm text-center py-8">
                Nenhuma assinatura encontrada.
            </p>
        </div>
    </AppLayout>
</template>
