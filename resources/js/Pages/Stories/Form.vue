<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    story: { type: Object, default: null },
});

const isEditing = computed(() => !!props.story);
const imagePreview = ref(props.story?.image || null);

const form = useForm({
    title: props.story?.title || '',
    image: null,
    active: props.story?.active ?? true,
});

const handleImage = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    const url = isEditing.value ? `/stories/${props.story.id}` : '/stories';

    if (isEditing.value) {
        form.post(url, {
            _method: 'PUT',
            forceFormData: true,
        });
    } else {
        form.post(url, {
            forceFormData: true,
        });
    }
};
</script>

<template>
    <AppLayout :title="isEditing ? 'Editar Story' : 'Novo Story'">
        <div class="max-w-2xl">
            <div class="mb-8">
                <Link href="/stories" class="text-dark-400 hover:text-white text-sm mb-2 inline-flex items-center gap-1">
                    <Icon name="arrow-left" class="w-4 h-4" />
                    Voltar para stories
                </Link>
                <h1 class="text-2xl font-display font-bold text-white">
                    {{ isEditing ? 'Editar Story' : 'Novo Story' }}
                </h1>
            </div>

            <form @submit.prevent="submit" class="card space-y-6">
                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Imagem do story</label>
                    <p class="text-xs text-dark-500 mb-3">Formato vertical (9:16) recomendado para melhor visualizacao. Ex: 1080x1920px</p>

                    <div class="flex items-start gap-4">
                        <div v-if="imagePreview" class="w-32 h-56 rounded-lg overflow-hidden flex-shrink-0">
                            <img :src="imagePreview" class="w-full h-full object-cover" />
                        </div>
                        <div v-else class="w-32 h-56 rounded-lg bg-dark-800 flex flex-col items-center justify-center flex-shrink-0">
                            <Icon name="image" class="w-10 h-10 text-dark-500 mb-2" />
                            <span class="text-xs text-dark-500">9:16</span>
                        </div>
                        <div class="pt-2">
                            <label class="btn-secondary cursor-pointer text-sm">
                                <Icon name="download" class="w-4 h-4" />
                                <span>{{ imagePreview ? 'Trocar imagem' : 'Upload imagem' }}</span>
                                <input type="file" accept="image/*" class="hidden" @change="handleImage" />
                            </label>
                            <p class="text-xs text-dark-500 mt-2">Max 4MB. JPG, PNG ou WebP.</p>
                        </div>
                    </div>
                    <p v-if="form.errors.image" class="mt-2 text-sm text-red-400">{{ form.errors.image }}</p>
                </div>

                <!-- Title (optional) -->
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Titulo (opcional)</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="input-field"
                        placeholder="Ex: Promo do dia, Novidade..."
                    />
                    <p class="text-xs text-dark-500 mt-1">Aparece abaixo do circulo do story no cardapio</p>
                    <p v-if="form.errors.title" class="mt-1 text-sm text-red-400">{{ form.errors.title }}</p>
                </div>

                <!-- Active toggle -->
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.active" class="sr-only peer" />
                        <div class="w-11 h-6 bg-dark-600 rounded-full peer peer-checked:bg-emerald-500
                                    peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5
                                    after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5
                                    after:transition-all"></div>
                    </label>
                    <span class="text-sm text-dark-300 flex items-center gap-1.5">
                        <Icon name="check" class="w-4 h-4 text-emerald-400" />
                        Ativo (visivel no cardapio)
                    </span>
                </div>

                <!-- Submit -->
                <div class="flex gap-3 pt-4 border-t border-dark-700">
                    <button type="submit" :disabled="form.processing" class="btn-primary">
                        {{ form.processing ? 'Salvando...' : (isEditing ? 'Salvar alteracoes' : 'Adicionar story') }}
                    </button>
                    <Link href="/stories" class="btn-secondary">Cancelar</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
