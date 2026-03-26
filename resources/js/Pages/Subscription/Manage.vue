<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    subscription: Object,
    plans: Array,
});

const statusLabels = {
    active: 'Ativo',
    canceled: 'Cancelado',
    trial: 'Pendente',
    expired: 'Expirado',
};

const statusColors = {
    active: 'text-emerald-400 bg-emerald-500/10 ring-emerald-500/20',
    canceled: 'text-red-400 bg-red-500/10 ring-red-500/20',
    trial: 'text-amber-400 bg-amber-500/10 ring-amber-500/20',
    expired: 'text-dark-400 bg-dark-700/60 ring-dark-600/20',
};

const currentPlanData = props.plans.find(p => p.slug === props.subscription.plan) || props.plans[0];
</script>

<template>
    <AppLayout title="Minha Assinatura">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-display font-bold text-white">Minha Assinatura</h1>
            <p class="text-dark-400 mt-1">Gerencie seu plano e assinatura</p>
        </div>

        <div class="max-w-2xl space-y-6">
            <!-- Current plan card -->
            <div class="rounded-2xl border border-dark-700/60 bg-dark-900 p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-dark-400 mb-1">Plano atual</h3>
                        <p class="text-2xl font-display font-bold text-white">{{ currentPlanData.name }}</p>
                    </div>
                    <span
                        v-if="subscription.status"
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ring-1"
                        :class="statusColors[subscription.status] || statusColors.expired"
                    >
                        {{ statusLabels[subscription.status] || subscription.status }}
                    </span>
                    <span
                        v-else
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ring-1 text-dark-400 bg-dark-700/60 ring-dark-600/20"
                    >
                        Free
                    </span>
                </div>

                <div class="flex items-baseline gap-1 mb-4">
                    <span class="text-3xl font-display font-black text-white">{{ currentPlanData.price_display }}</span>
                    <span v-if="currentPlanData.price > 0" class="text-dark-400 text-sm">/mes</span>
                </div>

                <div v-if="subscription.expires_at" class="text-sm text-dark-400 mb-4">
                    Valido ate: <span class="text-white">{{ subscription.expires_at }}</span>
                </div>

                <!-- Features -->
                <ul class="space-y-2 mb-6">
                    <li
                        v-for="feature in currentPlanData.features"
                        :key="feature"
                        class="flex items-start gap-2 text-sm text-dark-300"
                    >
                        <Icon name="check" class="w-4 h-4 text-brand-500 flex-shrink-0 mt-0.5" />
                        <span>{{ feature }}</span>
                    </li>
                </ul>

                <Link
                    href="/plans"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-xl transition-colors duration-200"
                >
                    <Icon name="star" class="w-4 h-4" />
                    {{ subscription.plan === 'free' || !subscription.plan ? 'Fazer Upgrade' : 'Trocar Plano' }}
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
