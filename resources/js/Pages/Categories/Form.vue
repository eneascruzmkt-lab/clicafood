<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    category: {
        type: Object,
        default: null,
    },
});

const isEditing = !!props.category;
const imagePreview = ref(props.category?.image ? '/storage/' + props.category.image : null);

const form = useForm({
    name: props.category?.name || '',
    image: null,
    active: props.category?.active ?? true,
});

const handleImage = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    if (isEditing) {
        form.post(`/categories/${props.category.id}`, {
            _method: 'PUT',
            forceFormData: true,
        });
    } else {
        form.post('/categories', {
            forceFormData: true,
        });
    }
};
</script>

<template>
    <AppLayout :title="isEditing ? 'Editar Categoria' : 'Nova Categoria'">
        <div class="max-w-2xl">
            <div class="mb-8">
                <Link href="/categories" class="text-dark-400 hover:text-white text-sm mb-2 inline-flex items-center gap-1">
                    <Icon name="arrow-left" class="w-4 h-4" />
                    Voltar para categorias
                </Link>
                <h1 class="text-2xl font-display font-bold text-white">
                    {{ isEditing ? 'Editar Categoria' : 'Nova Categoria' }}
                </h1>
            </div>

            <form @submit.prevent="submit" class="card space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Nome da categoria</label>
                    <input v-model="form.name" type="text" class="input-field"
                           placeholder="Ex: Hamburgueres, Pizzas, Bebidas..." required />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-400">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Imagem</label>
                    <div class="flex items-center gap-4">
                        <div v-if="imagePreview"
                             class="w-20 h-20 rounded-lg overflow-hidden">
                            <img :src="imagePreview" class="w-full h-full object-cover" />
                        </div>
                        <div v-else class="w-20 h-20 rounded-lg bg-dark-700 flex items-center justify-center">
                            <Icon name="image" class="w-8 h-8 text-dark-500" />
                        </div>
                        <label class="btn-secondary cursor-pointer text-sm">
                            <span>{{ imagePreview ? 'Trocar imagem' : 'Upload imagem' }}</span>
                            <input type="file" accept="image/*" class="hidden" @change="handleImage" />
                        </label>
                    </div>
                    <p v-if="form.errors.image" class="mt-1 text-sm text-red-400">{{ form.errors.image }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.active" class="sr-only peer" />
                        <div class="w-11 h-6 bg-dark-600 rounded-full peer peer-checked:bg-brand-500
                                    peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5
                                    after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5
                                    after:transition-all"></div>
                    </label>
                    <span class="text-sm text-dark-300">Categoria ativa</span>
                </div>

                <div class="flex gap-3 pt-4 border-t border-dark-700">
                    <button type="submit" :disabled="form.processing" class="btn-primary">
                        {{ form.processing ? 'Salvando...' : (isEditing ? 'Salvar alteracoes' : 'Criar categoria') }}
                    </button>
                    <Link href="/categories" class="btn-secondary">Cancelar</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
