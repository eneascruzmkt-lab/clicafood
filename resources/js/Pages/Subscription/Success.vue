<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

defineProps({
    plan: String,
});
</script>

<template>
    <AppLayout title="Assinatura Confirmada">
        <div class="max-w-lg mx-auto text-center py-12">
            <div class="w-16 h-16 bg-emerald-500/15 rounded-full flex items-center justify-center mx-auto mb-6 ring-1 ring-emerald-500/20">
                <Icon name="check" class="w-8 h-8 text-emerald-500" />
            </div>

            <h1 class="text-2xl lg:text-3xl font-display font-bold text-white mb-3">
                Assinatura Confirmada!
            </h1>
            <p class="text-dark-400 mb-8">
                Seu plano <span class="text-white font-semibold capitalize">{{ plan }}</span> foi ativado com sucesso.
                Aproveite todos os recursos disponivels!
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <Link
                    href="/dashboard"
                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-xl transition-colors duration-200"
                >
                    <Icon name="dashboard" class="w-4 h-4" />
                    Ir para o Dashboard
                </Link>
                <Link
                    href="/subscription"
                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-dark-700 hover:bg-dark-600 text-white text-sm font-semibold rounded-xl transition-colors duration-200"
                >
                    Gerenciar Assinatura
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
