<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    qrCodes: Array,
    menuUrl: String,
});

const deleteQr = (id) => {
    if (confirm('Excluir este QR Code?')) {
        router.delete(`/qr-codes/${id}`);
    }
};
</script>

<template>
    <AppLayout title="QR Codes">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl lg:text-3xl font-display font-bold text-white">QR Codes</h1>
                <p class="text-dark-400 mt-1">QR Codes ilimitados para seu cardápio</p>
            </div>
            <Link href="/qr-codes/create" class="btn-primary">
                + Novo QR Code
            </Link>
        </div>

        <!-- Menu URL info -->
        <div class="card mb-6 bg-brand-500/5 border-brand-500/20">
            <p class="text-sm text-dark-300">
                URL do cardápio:
                <a :href="menuUrl" target="_blank" class="text-brand-400 font-mono">{{ menuUrl }}</a>
            </p>
        </div>

        <div v-if="qrCodes.length === 0" class="card text-center py-12">
            <p class="text-dark-400 text-lg mb-4">Nenhum QR Code criado</p>
            <Link href="/qr-codes/create" class="btn-primary">Criar QR Code</Link>
        </div>

        <div v-else class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="qr in qrCodes" :key="qr.id" class="card text-center">
                <h3 class="font-semibold text-white mb-3">{{ qr.label }}</h3>

                <!-- QR Preview placeholder -->
                <div class="w-40 h-40 mx-auto bg-white rounded-lg flex items-center justify-center mb-4 p-2">
                    <div class="text-dark-900 text-xs font-mono text-center">
                        <p class="text-4xl mb-1">📱</p>
                        <p>QR Code</p>
                    </div>
                </div>

                <div class="flex gap-2 justify-center mb-3">
                    <a :href="`/qr-codes/${qr.id}/download/png`"
                       class="btn-secondary text-xs px-3 py-1.5">PNG</a>
                    <a :href="`/qr-codes/${qr.id}/download/svg`"
                       class="btn-secondary text-xs px-3 py-1.5">SVG</a>
                </div>

                <div class="flex gap-2 justify-center">
                    <Link :href="`/qr-codes/${qr.id}/edit`"
                          class="text-dark-400 hover:text-white text-sm">Editar</Link>
                    <button @click="deleteQr(qr.id)"
                            class="text-dark-400 hover:text-red-400 text-sm">Excluir</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
