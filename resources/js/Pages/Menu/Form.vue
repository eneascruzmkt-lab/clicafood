<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    item: { type: Object, default: null },
    categories: Array,
});

const isEditing = computed(() => !!props.item);
const imagePreview = ref(props.item?.image ? '/storage/' + props.item.image : null);
const videoPreview = ref(null);
const videoSource = ref(
    props.item?.video_url
        ? (props.item.video_url.startsWith('http') ? 'external' : 'upload')
        : 'upload'
);

const form = useForm({
    name: props.item?.name || '',
    description: props.item?.description || '',
    price: props.item?.price || '',
    category_id: props.item?.category_id || '',
    image: null,
    video: null,
    video_url_external: props.item?.video_url?.startsWith('http') ? props.item.video_url : '',
    featured: props.item?.featured ?? false,
    available: props.item?.available ?? true,
    remove_video: false,
});

const hasExistingVideo = computed(() => isEditing.value && props.item?.video_url && !form.remove_video);

const handleImage = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const handleVideo = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.video = file;
        videoPreview.value = URL.createObjectURL(file);
    }
};

const removeVideo = () => {
    form.remove_video = true;
    form.video = null;
    videoPreview.value = null;
};

const submit = () => {
    const url = isEditing.value ? `/menu-items/${props.item.id}` : '/menu-items';

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
    <AppLayout :title="isEditing ? 'Editar Item' : 'Novo Item'">
        <div class="max-w-2xl">
            <div class="mb-8">
                <Link href="/menu-items" class="text-dark-400 hover:text-white text-sm mb-2 inline-flex items-center gap-1">
                    <Icon name="arrow-left" class="w-4 h-4" />
                    Voltar para cardapio
                </Link>
                <h1 class="text-2xl font-display font-bold text-white">
                    {{ isEditing ? 'Editar Item' : 'Novo Item do Cardapio' }}
                </h1>
            </div>

            <form @submit.prevent="submit" class="card space-y-6">
                <!-- Nome -->
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Nome do prato</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="input-field"
                        placeholder="Ex: X-Burger Especial"
                        required
                    />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-400">{{ form.errors.name }}</p>
                </div>

                <!-- Descricao -->
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Descricao</label>
                    <textarea
                        v-model="form.description"
                        class="input-field"
                        rows="3"
                        placeholder="Descreva o prato..."
                    ></textarea>
                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-400">{{ form.errors.description }}</p>
                </div>

                <!-- Preco + Categoria -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Preco (R$)</label>
                        <input
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            min="0"
                            class="input-field"
                            placeholder="29.90"
                            required
                        />
                        <p v-if="form.errors.price" class="mt-1 text-sm text-red-400">{{ form.errors.price }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Categoria</label>
                        <select v-model="form.category_id" class="input-field" required>
                            <option value="">Selecione...</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-400">{{ form.errors.category_id }}</p>
                    </div>
                </div>

                <!-- Imagem -->
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Imagem do prato</label>
                    <div class="flex items-center gap-4">
                        <div v-if="imagePreview" class="w-24 h-24 rounded-lg overflow-hidden">
                            <img :src="imagePreview" class="w-full h-full object-cover" />
                        </div>
                        <div v-else class="w-24 h-24 rounded-lg bg-dark-800 flex items-center justify-center">
                            <Icon name="image" class="w-8 h-8 text-dark-500" />
                        </div>
                        <label class="btn-secondary cursor-pointer text-sm">
                            <Icon name="download" class="w-4 h-4" />
                            <span>{{ imagePreview ? 'Trocar imagem' : 'Upload imagem' }}</span>
                            <input type="file" accept="image/*" class="hidden" @change="handleImage" />
                        </label>
                    </div>
                    <p v-if="form.errors.image" class="mt-1 text-sm text-red-400">{{ form.errors.image }}</p>
                </div>

                <!-- Video -->
                <div class="border border-brand-500/20 rounded-lg p-4 bg-brand-500/5">
                    <label class="flex items-center gap-2 text-sm font-bold text-brand-400 mb-3">
                        <Icon name="video" class="w-5 h-5" />
                        Video do prato (funcionalidade principal)
                    </label>

                    <!-- Existing video -->
                    <div
                        v-if="hasExistingVideo"
                        class="mb-3 p-3 bg-dark-800 rounded-lg flex items-center justify-between"
                    >
                        <span class="text-sm text-dark-300 flex items-center gap-2">
                            <Icon name="check" class="w-4 h-4 text-green-400" />
                            Video atual carregado
                        </span>
                        <button type="button" @click="removeVideo" class="text-sm text-red-400 hover:text-red-300">
                            Remover video
                        </button>
                    </div>

                    <!-- Source toggle -->
                    <div class="flex gap-2 mb-3">
                        <button
                            type="button"
                            @click="videoSource = 'upload'"
                            :class="videoSource === 'upload' ? 'bg-brand-500 text-white' : 'bg-dark-700 text-dark-300'"
                            class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors inline-flex items-center gap-1.5"
                        >
                            <Icon name="download" class="w-4 h-4" />
                            Upload MP4
                        </button>
                        <button
                            type="button"
                            @click="videoSource = 'external'"
                            :class="videoSource === 'external' ? 'bg-brand-500 text-white' : 'bg-dark-700 text-dark-300'"
                            class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors inline-flex items-center gap-1.5"
                        >
                            <Icon name="external-link" class="w-4 h-4" />
                            Link externo
                        </button>
                    </div>

                    <div v-if="videoSource === 'upload'">
                        <label class="btn-secondary cursor-pointer text-sm inline-flex items-center gap-2">
                            <Icon name="video" class="w-4 h-4" />
                            <span>Selecionar video (MP4, max 50MB)</span>
                            <input
                                type="file"
                                accept="video/mp4,video/webm,video/quicktime"
                                class="hidden"
                                @change="handleVideo"
                            />
                        </label>
                        <div v-if="videoPreview" class="mt-3">
                            <video :src="videoPreview" controls class="w-full max-w-sm rounded-lg"></video>
                        </div>
                    </div>

                    <div v-else>
                        <input
                            v-model="form.video_url_external"
                            type="url"
                            class="input-field"
                            placeholder="https://exemplo.com/video.mp4"
                        />
                    </div>

                    <p v-if="form.errors.video" class="mt-1 text-sm text-red-400">{{ form.errors.video }}</p>
                </div>

                <!-- Toggles -->
                <div class="flex flex-wrap gap-6">
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" v-model="form.featured" class="sr-only peer" />
                            <div class="w-11 h-6 bg-dark-600 rounded-full peer peer-checked:bg-yellow-500
                                        peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5
                                        after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5
                                        after:transition-all"></div>
                        </label>
                        <span class="text-sm text-dark-300 flex items-center gap-1.5">
                            <Icon name="star" class="w-4 h-4 text-yellow-500" />
                            Destaque (aparece nos Stories)
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" v-model="form.available" class="sr-only peer" />
                            <div class="w-11 h-6 bg-dark-600 rounded-full peer peer-checked:bg-green-500
                                        peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5
                                        after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5
                                        after:transition-all"></div>
                        </label>
                        <span class="text-sm text-dark-300 flex items-center gap-1.5">
                            <Icon name="check" class="w-4 h-4 text-green-400" />
                            Disponivel
                        </span>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex gap-3 pt-4 border-t border-dark-700">
                    <button type="submit" :disabled="form.processing" class="btn-primary">
                        {{ form.processing ? 'Salvando...' : (isEditing ? 'Salvar alteracoes' : 'Adicionar ao cardapio') }}
                    </button>
                    <Link href="/menu-items" class="btn-secondary">Cancelar</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
