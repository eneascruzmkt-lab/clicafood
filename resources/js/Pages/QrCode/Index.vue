<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import QRCodeLib from 'qrcode';

const props = defineProps({
    qrCodes: Array,
    menuUrl: String,
});

const qrCanvases = ref({});

const deleteQr = (id) => {
    if (confirm('Excluir este QR Code?')) {
        router.delete(`/qr-codes/${id}`);
    }
};

const generateQrPreview = async (qr) => {
    await nextTick();
    const canvas = document.getElementById(`qr-canvas-${qr.id}`);
    if (!canvas) return;

    const color = qr.style_config?.color || '#E63B2E';
    const bg = qr.style_config?.background || '#FFFFFF';

    try {
        await QRCodeLib.toCanvas(canvas, qr.url, {
            width: 160,
            margin: 1,
            color: {
                dark: color,
                light: bg,
            },
        });
    } catch (err) {
        console.error('QR generation error:', err);
    }
};

const downloadQr = async (qr, format) => {
    const color = qr.style_config?.color || '#E63B2E';
    const bg = qr.style_config?.background || '#FFFFFF';

    if (format === 'svg') {
        try {
            const svgString = await QRCodeLib.toString(qr.url, {
                type: 'svg',
                width: 400,
                margin: 1,
                color: { dark: color, light: bg },
            });
            const blob = new Blob([svgString], { type: 'image/svg+xml' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `qrcode-${qr.label}.svg`;
            a.click();
            URL.revokeObjectURL(url);
        } catch (err) {
            console.error(err);
        }
    } else {
        try {
            const dataUrl = await QRCodeLib.toDataURL(qr.url, {
                width: 800,
                margin: 1,
                color: { dark: color, light: bg },
            });
            const a = document.createElement('a');
            a.href = dataUrl;
            a.download = `qrcode-${qr.label}.png`;
            a.click();
        } catch (err) {
            console.error(err);
        }
    }
};

onMounted(() => {
    props.qrCodes.forEach((qr) => generateQrPreview(qr));
});
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

                <!-- QR Code real preview -->
                <div class="w-44 h-44 mx-auto bg-white rounded-lg flex items-center justify-center mb-4 p-2">
                    <canvas :id="`qr-canvas-${qr.id}`"></canvas>
                </div>

                <p class="text-xs text-dark-500 mb-3 font-mono truncate px-2">{{ qr.url }}</p>

                <div class="flex gap-2 justify-center mb-3">
                    <button @click="downloadQr(qr, 'png')"
                            class="btn-secondary text-xs px-3 py-1.5">
                        Download PNG
                    </button>
                    <button @click="downloadQr(qr, 'svg')"
                            class="btn-secondary text-xs px-3 py-1.5">
                        Download SVG
                    </button>
                </div>

                <div class="flex gap-3 justify-center pt-2 border-t border-dark-700">
                    <Link :href="`/qr-codes/${qr.id}/edit`"
                          class="text-dark-400 hover:text-white text-sm transition-colors">Editar</Link>
                    <button @click="deleteQr(qr.id)"
                            class="text-dark-400 hover:text-red-400 text-sm transition-colors">Excluir</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
