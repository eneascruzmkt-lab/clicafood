<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';
import axios from 'axios';

const props = defineProps({
    item: { type: Object, default: null },
    categories: Array,
});

const isEditing = computed(() => !!props.item);

const getMediaUrl = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};

const imagePreview = ref(getMediaUrl(props.item?.image));
const videoPreview = ref(null);
const thumbnailPreview = ref(getMediaUrl(props.item?.video_thumbnail));

const form = useForm({
    name: props.item?.name || '',
    description: props.item?.description || '',
    price: props.item?.price || '',
    category_id: props.item?.category_id || '',
    image: null,
    video: null,
    thumbnail: null,
    video_url_external: '',
    featured: props.item?.featured ?? false,
    available: props.item?.available ?? true,
    remove_video: false,
    remove_thumbnail: false,
});

const hasExistingVideo = computed(() => isEditing.value && props.item?.video_url && !form.remove_video);
const hasExistingThumbnail = computed(() => (isEditing.value && props.item?.video_thumbnail && !form.remove_thumbnail) || thumbnailPreview.value);

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

const handleThumbnail = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.thumbnail = file;
        form.remove_thumbnail = false;
        thumbnailPreview.value = URL.createObjectURL(file);
    }
};

const removeVideo = () => {
    form.remove_video = true;
    form.video = null;
    videoPreview.value = null;
};

const removeThumbnail = () => {
    form.remove_thumbnail = true;
    form.thumbnail = null;
    thumbnailPreview.value = null;
};

// AR Model
const arStatus = ref(props.item?.model_status || null);
const arProgress = ref(0);
let arPollTimer = null;

const generateAr = () => {
    if (!props.item?.id) return;
    router.post(`/menu-items/${props.item.id}/ar/generate`, {}, {
        preserveScroll: true,
        onSuccess: () => { arStatus.value = 'processing'; startArPolling(); },
    });
};

const removeAr = () => {
    if (!props.item?.id) return;
    router.delete(`/menu-items/${props.item.id}/ar`, { preserveScroll: true,
        onSuccess: () => { arStatus.value = null; },
    });
};

const startArPolling = () => {
    if (arPollTimer) clearInterval(arPollTimer);
    arPollTimer = setInterval(async () => {
        try {
            const { data } = await axios.get(`/menu-items/${props.item.id}/ar/status`);
            arProgress.value = data.progress || 0;
            if (data.status === 'ready' || data.status === 'failed') {
                arStatus.value = data.status;
                clearInterval(arPollTimer);
            }
        } catch (e) {}
    }, 5000);
};

onMounted(() => {
    if (arStatus.value && arStatus.value.startsWith('processing')) startArPolling();
});
onUnmounted(() => { if (arPollTimer) clearInterval(arPollTimer); });

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
                        Video do prato
                    </label>

                    <!-- Existing video -->
                    <div
                        v-if="hasExistingVideo"
                        class="mb-3 p-3 bg-dark-800 rounded-lg flex items-center justify-between"
                    >
                        <span class="text-sm text-dark-300 flex items-center gap-2">
                            <Icon name="check" class="w-4 h-4 text-emerald-400" />
                            Video atual carregado
                        </span>
                        <button type="button" @click="removeVideo" class="text-sm text-red-400 hover:text-red-300">
                            Remover video
                        </button>
                    </div>

                    <div>
                        <label class="btn-secondary cursor-pointer text-sm inline-flex items-center gap-2">
                            <Icon name="video" class="w-4 h-4" />
                            <span>Selecionar video (MP4, max 100MB)</span>
                            <input
                                type="file"
                                accept="video/mp4,video/webm,video/quicktime"
                                class="hidden"
                                @change="handleVideo"
                            />
                        </label>
                        <p class="text-xs text-dark-500 mt-1.5">O video sera comprimido automaticamente para carregamento rapido</p>
                        <div v-if="videoPreview" class="mt-3">
                            <video :src="videoPreview" controls class="w-full max-w-sm rounded-lg"></video>
                        </div>
                    </div>

                    <p v-if="form.errors.video" class="mt-1 text-sm text-red-400">{{ form.errors.video }}</p>
                </div>

                <!-- Thumbnail -->
                <div class="border border-purple-500/20 rounded-lg p-4 bg-purple-500/5">
                    <label class="flex items-center gap-2 text-sm font-bold text-purple-400 mb-3">
                        <Icon name="image" class="w-5 h-5" />
                        Thumbnail do video
                    </label>
                    <p class="text-xs text-dark-400 mb-3">Imagem de capa exibida antes do video iniciar. Se nao enviar, sera usado a imagem do prato.</p>

                    <div class="flex items-center gap-4">
                        <div v-if="thumbnailPreview" class="relative w-20 h-32 rounded-lg overflow-hidden">
                            <img :src="thumbnailPreview" class="w-full h-full object-cover" />
                            <button type="button" @click="removeThumbnail"
                                    class="absolute top-1 right-1 w-5 h-5 rounded-full bg-black/60 flex items-center justify-center text-white/80 hover:text-white">
                                <Icon name="close" class="w-3 h-3" />
                            </button>
                        </div>
                        <div v-else class="w-20 h-32 rounded-lg bg-dark-800 flex items-center justify-center">
                            <Icon name="image" class="w-6 h-6 text-dark-500" />
                        </div>
                        <div>
                            <label class="btn-secondary cursor-pointer text-sm">
                                <Icon name="download" class="w-4 h-4" />
                                <span>{{ thumbnailPreview ? 'Trocar thumbnail' : 'Upload thumbnail' }}</span>
                                <input type="file" accept="image/*" class="hidden" @change="handleThumbnail" />
                            </label>
                            <p class="text-xs text-dark-500 mt-1">Formato vertical (9:16) recomendado</p>
                        </div>
                    </div>
                    <p v-if="form.errors.thumbnail" class="mt-1 text-sm text-red-400">{{ form.errors.thumbnail }}</p>
                </div>

                <!-- Toggles -->
                <div class="flex flex-wrap gap-6">
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" v-model="form.featured" class="sr-only peer" />
                            <div class="w-11 h-6 bg-dark-600 rounded-full peer peer-checked:bg-amber-500
                                        peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5
                                        after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5
                                        after:transition-all"></div>
                        </label>
                        <span class="text-sm text-dark-300 flex items-center gap-1.5">
                            <Icon name="star" class="w-4 h-4 text-amber-500" />
                            Destaque (aparece nos Stories)
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" v-model="form.available" class="sr-only peer" />
                            <div class="w-11 h-6 bg-dark-600 rounded-full peer peer-checked:bg-emerald-500
                                        peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5
                                        after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5
                                        after:transition-all"></div>
                        </label>
                        <span class="text-sm text-dark-300 flex items-center gap-1.5">
                            <Icon name="check" class="w-4 h-4 text-emerald-400" />
                            Disponivel
                        </span>
                    </div>
                </div>

                <!-- AR 3D Model -->
                <div v-if="isEditing && props.item?.image" class="card space-y-4">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                        </svg>
                        Modelo 3D / Realidade Aumentada
                    </h3>

                    <!-- Ready -->
                    <div v-if="arStatus === 'ready'" class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                            <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-emerald-400 font-semibold">Modelo 3D pronto!</p>
                            <p class="text-xs text-dark-400 mt-0.5">Os clientes podem ver este prato em realidade aumentada.</p>
                        </div>
                        <button type="button" @click="removeAr" class="btn-secondary text-xs py-1.5 px-3">Remover</button>
                    </div>

                    <!-- Processing -->
                    <div v-else-if="arStatus && arStatus.startsWith('processing')" class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-blue-400 font-semibold">Gerando modelo 3D...</p>
                            <p class="text-xs text-dark-400 mt-0.5">Isso pode levar de 1 a 5 minutos. Você pode sair desta página.</p>
                            <div class="w-full bg-dark-700 rounded-full h-1.5 mt-2">
                                <div class="bg-blue-400 h-1.5 rounded-full transition-all duration-500" :style="{ width: arProgress + '%' }"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Failed -->
                    <div v-else-if="arStatus === 'failed'" class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-red-500/10 flex items-center justify-center">
                            <Icon name="close" class="w-8 h-8 text-red-400" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-red-400 font-semibold">Falha ao gerar modelo</p>
                            <p class="text-xs text-dark-400 mt-0.5">Tente novamente com uma foto melhor do prato.</p>
                        </div>
                        <button type="button" @click="generateAr" class="btn-primary text-xs py-1.5 px-3">Tentar novamente</button>
                    </div>

                    <!-- Generate button -->
                    <div v-else>
                        <p class="text-xs text-dark-400 mb-3">Gere um modelo 3D a partir da foto do prato para que seus clientes possam ver em realidade aumentada.</p>
                        <button type="button" @click="generateAr" class="btn-primary text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                            </svg>
                            Gerar Modelo 3D
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex gap-3 pt-4 border-t border-dark-700">
                    <button type="submit" :disabled="form.processing" class="btn-primary">
                        <template v-if="form.processing">
                            <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processando video...
                        </template>
                        <template v-else>
                            {{ isEditing ? 'Salvar alteracoes' : 'Adicionar ao cardapio' }}
                        </template>
                    </button>
                    <Link href="/menu-items" class="btn-secondary">Cancelar</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
