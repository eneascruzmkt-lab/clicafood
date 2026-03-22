<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

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
</script>

<template>
    <AppLayout title="Admin - Planos">
        <div class="mb-8">
            <h1 class="text-2xl font-display font-bold text-white">Gestão de Planos</h1>
        </div>

        <div class="card overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-dark-400 border-b border-dark-700">
                        <th class="pb-3 font-medium">Usuário</th>
                        <th class="pb-3 font-medium">Plano</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium">Início</th>
                        <th class="pb-3 font-medium">Fim</th>
                        <th class="pb-3 font-medium">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="sub in subscriptions.data" :key="sub.id"
                        class="border-b border-dark-800 last:border-0">
                        <td class="py-3 text-white">{{ sub.user?.name }} ({{ sub.user?.email }})</td>
                        <td class="py-3">
                            <span :class="sub.plan === 'pro' ? 'bg-brand-500/20 text-brand-400' : 'bg-dark-700 text-dark-400'"
                                  class="px-2 py-0.5 rounded-full text-xs font-medium uppercase">
                                {{ sub.plan }}
                            </span>
                        </td>
                        <td class="py-3">
                            <span :class="{
                                'text-green-400': sub.status === 'active',
                                'text-red-400': sub.status === 'cancelled',
                                'text-yellow-400': sub.status === 'expired',
                            }" class="text-xs font-medium">
                                {{ sub.status }}
                            </span>
                        </td>
                        <td class="py-3 text-dark-400 text-xs">
                            {{ sub.starts_at ? new Date(sub.starts_at).toLocaleDateString('pt-BR') : '-' }}
                        </td>
                        <td class="py-3 text-dark-400 text-xs">
                            {{ sub.ends_at ? new Date(sub.ends_at).toLocaleDateString('pt-BR') : 'Indefinido' }}
                        </td>
                        <td class="py-3">
                            <button v-if="sub.status === 'active'" @click="updateStatus(sub, 'cancelled')"
                                    class="text-red-400 hover:text-red-300 text-xs">Cancelar</button>
                            <button v-else @click="updateStatus(sub, 'active')"
                                    class="text-green-400 hover:text-green-300 text-xs">Ativar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p v-if="subscriptions.data?.length === 0" class="text-dark-400 text-sm text-center py-8">
                Nenhuma assinatura encontrada.
            </p>
        </div>
    </AppLayout>
</template>
