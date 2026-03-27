<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    plans: Array,
    currentPlan: String,
});

const loading = ref(false);
const errorMsg = ref('');

const subscribe = (planSlug) => {
    loading.value = true;
    errorMsg.value = '';
    router.post(`/subscribe/${planSlug}`, {}, {
        onError: (errors) => {
            loading.value = false;
            errorMsg.value = Object.values(errors).flat().join(', ') || 'Erro ao processar.';
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const isCurrentPlan = (planSlug) => {
    return props.currentPlan === planSlug;
};

const features = [
    'Cardápio Video-First Ilimitado',
    'QR Codes Dinâmicos Ilimitados',
    'Dashboard de Métricas Avançadas',
    'Suporte Prioritário 24/7',
    'Integração com WhatsApp',
    'Vídeos dos pratos',
];
</script>

<template>
    <AppLayout title="Assinar Plano">
        <div class="max-w-lg mx-auto py-8">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-display font-bold text-white">Domine seu cardápio</h1>
                <p class="text-dark-400 mt-2">Assine o plano Pro e tenha acesso completo a todas as funcionalidades.</p>
            </div>

            <div class="card border-brand-500/30 text-center relative overflow-hidden">
                <!-- Badge -->
                <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 text-white text-xs font-bold py-1.5 uppercase tracking-widest">
                    -20% OFF
                </div>

                <div class="pt-10">
                    <h2 class="font-display font-bold text-2xl text-white">Plano Pro</h2>
                    <p class="text-dark-400 text-sm mt-1">Tudo o que você precisa para dominar.</p>

                    <div class="mt-6 mb-8">
                        <span class="text-dark-500 text-sm line-through">R$ 197/mês</span>
                        <p class="text-5xl font-display font-black text-white mt-1">
                            R$ <span class="text-brand-500">97</span><span class="text-lg text-dark-400 font-semibold">/mês</span>
                        </p>
                    </div>

                    <ul class="text-sm text-dark-300 space-y-3 text-left mb-8">
                        <li v-for="feature in features" :key="feature" class="flex items-center gap-3">
                            <Icon name="check" class="w-4 h-4 text-emerald-400 shrink-0" />
                            <span>{{ feature }}</span>
                        </li>
                    </ul>

                    <button
                        v-if="isCurrentPlan('pro')"
                        disabled
                        class="w-full py-4 rounded-xl text-lg font-bold bg-dark-700/60 text-dark-400 cursor-not-allowed"
                    >
                        Plano atual
                    </button>
                    <button
                        v-else
                        @click="subscribe('pro')"
                        :disabled="loading"
                        class="btn-primary w-full py-4 text-lg font-bold shadow-lg shadow-brand-500/25"
                        :class="{ 'opacity-60 cursor-wait': loading }"
                    >
                        {{ loading ? 'Redirecionando...' : 'Quero dominar' }}
                    </button>

                    <p v-if="errorMsg" class="text-sm text-red-400 mt-3 text-center">{{ errorMsg }}</p>
                    <p v-if="$page.props.flash?.error" class="text-sm text-red-400 mt-3 text-center">{{ $page.props.flash.error }}</p>
                    <p class="text-xs text-dark-500 mt-4">7 dias grátis. Cancele quando quiser. Sem fidelidade.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
