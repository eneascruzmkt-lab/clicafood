<script setup>
import { ref, watch, onMounted, nextTick } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import QRCodeLib from 'qrcode';

const props = defineProps({
    qrCode: { type: Object, default: null },
    menuUrl: String,
});

const isEditing = !!props.qrCode;

const form = useForm({
    label: props.qrCode?.label || '',
    style_config: {
        color: props.qrCode?.style_config?.color || '#E63B2E',
        background: props.qrCode?.style_config?.background || '#FFFFFF',
    },
});

const renderPreview = async () => {
    await nextTick();
    const canvas = document.getElementById('qr-preview');
    if (!canvas) return;

    try {
        await QRCodeLib.toCanvas(canvas, props.menuUrl || 'https://clicafood.com', {
            width: 200,
            margin: 1,
            color: {
                dark: form.style_config.color,
                light: form.style_config.background,
            },
        });
    } catch (err) {
        console.error(err);
    }
};

watch(() => [form.style_config.color, form.style_config.background], renderPreview, { deep: true });
onMounted(renderPreview);

const submit = () => {
    if (isEditing) {
        form.put(`/qr-codes/${props.qrCode.id}`);
    } else {
        form.post('/qr-codes');
    }
};
</script>

<template>
    <AppLayout :title="isEditing ? 'Editar QR Code' : 'Novo QR Code'">
        <div class="max-w-lg">
            <div class="mb-8">
                <Link href="/qr-codes" class="text-dark-400 hover:text-white text-sm mb-2 inline-block">
                    ← Voltar
                </Link>
                <h1 class="text-2xl font-display font-bold text-white">
                    {{ isEditing ? 'Editar QR Code' : 'Novo QR Code' }}
                </h1>
            </div>

            <form @submit.prevent="submit" class="card space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Nome/Rótulo</label>
                    <input v-model="form.label" type="text" class="input-field"
                           placeholder="Ex: Mesa 1, Balcão, Delivery..." required />
                    <p v-if="form.errors.label" class="mt-1 text-sm text-red-400">{{ form.errors.label }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Cor do QR</label>
                        <div class="flex items-center gap-2">
                            <input v-model="form.style_config.color" type="color"
                                   class="w-10 h-10 rounded border border-dark-600 cursor-pointer bg-transparent" />
                            <input v-model="form.style_config.color" type="text"
                                   class="input-field flex-1 text-xs font-mono" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Fundo</label>
                        <div class="flex items-center gap-2">
                            <input v-model="form.style_config.background" type="color"
                                   class="w-10 h-10 rounded border border-dark-600 cursor-pointer bg-transparent" />
                            <input v-model="form.style_config.background" type="text"
                                   class="input-field flex-1 text-xs font-mono" />
                        </div>
                    </div>
                </div>

                <!-- Live Preview -->
                <div class="text-center">
                    <p class="text-sm font-medium text-dark-300 mb-3">Preview</p>
                    <div class="inline-block bg-white rounded-xl p-3 shadow-lg">
                        <canvas id="qr-preview"></canvas>
                    </div>
                </div>

                <div class="p-3 bg-dark-800 rounded-lg">
                    <p class="text-xs text-dark-400">URL do cardápio:</p>
                    <p class="text-sm text-brand-400 font-mono">{{ menuUrl }}</p>
                </div>

                <div class="flex gap-3 pt-4 border-t border-dark-700">
                    <button type="submit" :disabled="form.processing" class="btn-primary">
                        {{ form.processing ? 'Salvando...' : (isEditing ? 'Salvar' : 'Criar QR Code') }}
                    </button>
                    <Link href="/qr-codes" class="btn-secondary">Cancelar</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
