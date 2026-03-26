<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    plans: Array,
    currentPlan: String,
});

const subscribe = (planSlug) => {
    if (planSlug === 'free') return;
    router.post(`/subscribe/${planSlug}`);
};

const isCurrentPlan = (planSlug) => {
    return props.currentPlan === planSlug;
};
</script>

<template>
    <AppLayout title="Planos">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-display font-bold text-white">Planos</h1>
            <p class="text-dark-400 mt-1">Escolha o plano ideal para o seu restaurante</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl">
            <div
                v-for="plan in plans"
                :key="plan.slug"
                class="relative rounded-2xl border p-6 flex flex-col"
                :class="plan.slug === 'pro'
                    ? 'border-brand-500 bg-dark-800/80 ring-1 ring-brand-500/30'
                    : 'border-dark-700/60 bg-dark-900'"
            >
                <!-- Popular badge -->
                <div
                    v-if="plan.slug === 'pro'"
                    class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-brand-500 text-white text-xs font-bold rounded-full uppercase tracking-wider"
                >
                    Popular
                </div>

                <!-- Plan header -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-white mb-1">{{ plan.name }}</h3>
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-display font-black text-white">{{ plan.price_display }}</span>
                        <span v-if="plan.price > 0" class="text-dark-400 text-sm">/mes</span>
                    </div>
                </div>

                <!-- Features -->
                <ul class="space-y-3 mb-8 flex-1">
                    <li
                        v-for="feature in plan.features"
                        :key="feature"
                        class="flex items-start gap-2 text-sm text-dark-300"
                    >
                        <Icon name="check" class="w-4 h-4 text-brand-500 flex-shrink-0 mt-0.5" />
                        <span>{{ feature }}</span>
                    </li>
                </ul>

                <!-- Action button -->
                <button
                    v-if="isCurrentPlan(plan.slug)"
                    disabled
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold bg-dark-700/60 text-dark-400 cursor-not-allowed"
                >
                    Plano atual
                </button>
                <button
                    v-else-if="plan.slug === 'free'"
                    disabled
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold bg-dark-700/60 text-dark-400 cursor-not-allowed"
                >
                    Gratuito
                </button>
                <button
                    v-else
                    @click="subscribe(plan.slug)"
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200"
                    :class="plan.slug === 'pro'
                        ? 'bg-brand-500 hover:bg-brand-600 text-white shadow-lg shadow-brand-500/25'
                        : 'bg-dark-700 hover:bg-dark-600 text-white'"
                >
                    Assinar {{ plan.name }}
                </button>
            </div>
        </div>
    </AppLayout>
</template>
