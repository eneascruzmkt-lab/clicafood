<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    restaurant: Object,
});

const mediaUrl = (path) => path ? (path.startsWith('http') ? path : '/storage/' + path) : null;
const logoPreview = ref(props.restaurant?.logo_url || mediaUrl(props.restaurant?.logo));
const bgPreview = ref(props.restaurant?.background_image || null);

const form = useForm({
    name: props.restaurant?.name || '',
    description: props.restaurant?.description || '',
    logo: null,
    primary_color: props.restaurant?.primary_color || '#E63B2E',
    secondary_color: props.restaurant?.secondary_color || '#1a1a1a',
    text_color: props.restaurant?.text_color || '#ffffff',
    text_secondary_color: props.restaurant?.text_secondary_color || '#9ca3af',
    border_color: props.restaurant?.border_color || '#333333',
    price_color: props.restaurant?.price_color || props.restaurant?.primary_color || '#E63B2E',
    font_family: props.restaurant?.font_family || 'Inter',
    background_image: null,
    background_opacity: props.restaurant?.background_opacity ?? 100,
    remove_background: false,
    address: props.restaurant?.address || '',
    phone: props.restaurant?.phone || '',
    instagram: props.restaurant?.instagram || '',
    whatsapp: props.restaurant?.whatsapp || '',
    working_hours: props.restaurant?.working_hours || {},
});

const fonts = [
    { value: 'Inter', label: 'Inter (Moderno)' },
    { value: 'Poppins', label: 'Poppins (Arredondado)' },
    { value: 'Playfair Display', label: 'Playfair (Elegante)' },
    { value: 'Roboto', label: 'Roboto (Clean)' },
    { value: 'Montserrat', label: 'Montserrat (Geometrico)' },
    { value: 'Lato', label: 'Lato (Classico)' },
    { value: 'Oswald', label: 'Oswald (Condensado)' },
    { value: 'Dancing Script', label: 'Dancing Script (Manuscrito)' },
];

const handleLogo = (e) => {
    const file = e.target.files[0];
    if (file) { form.logo = file; logoPreview.value = URL.createObjectURL(file); }
};

const handleBackground = (e) => {
    const file = e.target.files[0];
    if (file) { form.background_image = file; form.remove_background = false; bgPreview.value = URL.createObjectURL(file); }
};

const removeBackground = () => {
    form.remove_background = true;
    form.background_image = null;
    bgPreview.value = null;
};

const submit = () => {
    form.post('/settings', { forceFormData: true, preserveScroll: true });
};

// Preview computed styles
const previewBg = computed(() => form.secondary_color);
const previewText = computed(() => form.text_color);
const previewTextSec = computed(() => form.text_secondary_color);
const previewPrimary = computed(() => form.primary_color);
const previewPrice = computed(() => form.price_color || form.primary_color);
const previewBorder = computed(() => form.border_color);
const previewFont = computed(() => `'${form.font_family}', sans-serif`);
const previewOpacity = computed(() => form.background_opacity / 100);

const activeTab = ref('identity');
</script>

<template>
    <AppLayout title="Configuracoes">
        <div class="flex gap-8">
            <!-- Left: Settings form -->
            <div class="flex-1 max-w-xl">
                <div class="mb-8">
                    <h1 class="text-2xl font-display font-bold text-white">Configuracoes</h1>
                    <p class="text-dark-400 mt-1">Personalize seu cardapio digital</p>
                </div>

                <!-- Tabs -->
                <div class="flex gap-1 mb-6 bg-dark-800 rounded-lg p-1">
                    <button @click="activeTab = 'identity'" :class="activeTab === 'identity' ? 'bg-dark-600 text-white' : 'text-dark-400'"
                            class="flex-1 px-3 py-2 rounded-md text-sm font-medium transition-colors">Identidade</button>
                    <button @click="activeTab = 'theme'" :class="activeTab === 'theme' ? 'bg-dark-600 text-white' : 'text-dark-400'"
                            class="flex-1 px-3 py-2 rounded-md text-sm font-medium transition-colors">Tema</button>
                    <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'bg-dark-600 text-white' : 'text-dark-400'"
                            class="flex-1 px-3 py-2 rounded-md text-sm font-medium transition-colors">Contato</button>
                </div>

                <form @submit.prevent="submit" class="space-y-6">

                    <!-- TAB: Identity -->
                    <div v-show="activeTab === 'identity'" class="space-y-6">
                        <div class="card space-y-4">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <Icon name="restaurant" class="w-5 h-5 text-brand-400" />
                                Logo e Nome
                            </h3>
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 rounded-xl overflow-hidden bg-dark-700 flex-shrink-0">
                                    <img v-if="logoPreview" :src="logoPreview" class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center">
                                        <Icon name="image" class="w-8 h-8 text-dark-500" />
                                    </div>
                                </div>
                                <label class="btn-secondary cursor-pointer text-sm">
                                    <span>{{ logoPreview ? 'Trocar logo' : 'Upload logo' }}</span>
                                    <input type="file" accept="image/*" class="hidden" @change="handleLogo" />
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-dark-300 mb-1">Nome do restaurante</label>
                                <input v-model="form.name" type="text" class="input-field" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-dark-300 mb-1">Descricao / Bio</label>
                                <textarea v-model="form.description" class="input-field" rows="3" placeholder="Fale sobre seu restaurante..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Theme -->
                    <div v-show="activeTab === 'theme'" class="space-y-6">
                        <!-- Colors -->
                        <div class="card space-y-4">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <Icon name="settings" class="w-5 h-5 text-brand-400" />
                                Cores
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-dark-400 mb-1">Cor primaria</label>
                                    <div class="flex items-center gap-2">
                                        <input v-model="form.primary_color" type="color" class="w-9 h-9 rounded border border-dark-600 cursor-pointer" />
                                        <input v-model="form.primary_color" type="text" class="input-field flex-1 text-xs" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-dark-400 mb-1">Fundo</label>
                                    <div class="flex items-center gap-2">
                                        <input v-model="form.secondary_color" type="color" class="w-9 h-9 rounded border border-dark-600 cursor-pointer" />
                                        <input v-model="form.secondary_color" type="text" class="input-field flex-1 text-xs" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-dark-400 mb-1">Texto principal</label>
                                    <div class="flex items-center gap-2">
                                        <input v-model="form.text_color" type="color" class="w-9 h-9 rounded border border-dark-600 cursor-pointer" />
                                        <input v-model="form.text_color" type="text" class="input-field flex-1 text-xs" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-dark-400 mb-1">Texto secundario</label>
                                    <div class="flex items-center gap-2">
                                        <input v-model="form.text_secondary_color" type="color" class="w-9 h-9 rounded border border-dark-600 cursor-pointer" />
                                        <input v-model="form.text_secondary_color" type="text" class="input-field flex-1 text-xs" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-dark-400 mb-1">Preco</label>
                                    <div class="flex items-center gap-2">
                                        <input v-model="form.price_color" type="color" class="w-9 h-9 rounded border border-dark-600 cursor-pointer" />
                                        <input v-model="form.price_color" type="text" class="input-field flex-1 text-xs" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-dark-400 mb-1">Bordas</label>
                                    <div class="flex items-center gap-2">
                                        <input v-model="form.border_color" type="color" class="w-9 h-9 rounded border border-dark-600 cursor-pointer" />
                                        <input v-model="form.border_color" type="text" class="input-field flex-1 text-xs" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Font -->
                        <div class="card space-y-4">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <Icon name="edit" class="w-5 h-5 text-brand-400" />
                                Tipografia
                            </h3>
                            <div>
                                <label class="block text-xs font-medium text-dark-400 mb-1">Fonte</label>
                                <select v-model="form.font_family" class="input-field">
                                    <option v-for="f in fonts" :key="f.value" :value="f.value" :style="{ fontFamily: f.value }">
                                        {{ f.label }}
                                    </option>
                                </select>
                            </div>
                            <p class="text-sm p-3 rounded-lg bg-dark-800" :style="{ fontFamily: previewFont, color: form.text_color }">
                                Exemplo de texto com a fonte <strong>{{ form.font_family }}</strong>
                            </p>
                        </div>

                        <!-- Background -->
                        <div class="card space-y-4">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <Icon name="image" class="w-5 h-5 text-brand-400" />
                                Imagem de fundo
                            </h3>
                            <div class="flex items-center gap-4">
                                <div v-if="bgPreview" class="relative w-24 h-40 rounded-lg overflow-hidden">
                                    <img :src="bgPreview" class="w-full h-full object-cover" />
                                    <button type="button" @click="removeBackground"
                                            class="absolute top-1 right-1 w-5 h-5 rounded-full bg-black/60 flex items-center justify-center text-white/80 hover:text-white">
                                        <Icon name="close" class="w-3 h-3" />
                                    </button>
                                </div>
                                <div v-else class="w-24 h-40 rounded-lg bg-dark-800 flex items-center justify-center">
                                    <Icon name="image" class="w-8 h-8 text-dark-500" />
                                </div>
                                <div>
                                    <label class="btn-secondary cursor-pointer text-sm">
                                        <span>{{ bgPreview ? 'Trocar' : 'Upload' }}</span>
                                        <input type="file" accept="image/*" class="hidden" @change="handleBackground" />
                                    </label>
                                    <p class="text-xs text-dark-500 mt-1">Aparece atras do cardapio</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-dark-400 mb-2">
                                    Opacidade do fundo: {{ form.background_opacity }}%
                                </label>
                                <input v-model.number="form.background_opacity" type="range" min="0" max="100" step="5"
                                       class="w-full accent-brand-500" />
                                <div class="flex justify-between text-[10px] text-dark-500 mt-1">
                                    <span>Transparente</span><span>Opaco</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Contact -->
                    <div v-show="activeTab === 'contact'" class="space-y-6">
                        <div class="card space-y-4">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <Icon name="phone" class="w-5 h-5 text-brand-400" />
                                Contato e Redes
                            </h3>
                            <div>
                                <label class="block text-sm font-medium text-dark-300 mb-1">Endereco</label>
                                <input v-model="form.address" type="text" class="input-field" placeholder="Rua, numero, bairro..." />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-dark-300 mb-1">Telefone</label>
                                    <input v-model="form.phone" type="text" class="input-field" placeholder="(11) 3333-4444" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-dark-300 mb-1">WhatsApp</label>
                                    <input v-model="form.whatsapp" type="text" class="input-field" placeholder="5511999999999" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-dark-300 mb-1">Instagram</label>
                                <div class="flex items-center gap-2">
                                    <Icon name="instagram" class="w-5 h-5 text-dark-400" />
                                    <input v-model="form.instagram" type="text" class="input-field flex-1" placeholder="@seurestaurante" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" :disabled="form.processing" class="btn-primary w-full">
                        {{ form.processing ? 'Salvando...' : 'Salvar configuracoes' }}
                    </button>
                </form>
            </div>

            <!-- Right: Phone Mockup Preview -->
            <div class="hidden lg:block w-[320px] flex-shrink-0">
                <div class="sticky top-24">
                    <p class="text-xs text-dark-500 text-center mb-3">Preview em tempo real</p>

                    <!-- Phone frame -->
                    <div class="relative mx-auto w-[280px] h-[560px] rounded-[2.5rem] border-[6px] border-dark-600 bg-dark-800 shadow-2xl overflow-hidden">
                        <!-- Notch -->
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-5 bg-dark-600 rounded-b-xl z-20"></div>

                        <!-- Screen content -->
                        <div class="h-full w-full overflow-y-auto overflow-x-hidden relative" :style="{ backgroundColor: previewBg, fontFamily: previewFont }">

                            <!-- Background image -->
                            <div v-if="bgPreview" class="absolute inset-0 z-0">
                                <img :src="bgPreview" class="w-full h-full object-cover" :style="{ opacity: previewOpacity }" />
                            </div>

                            <div class="relative z-10 px-4 pt-8 pb-4">
                                <!-- Header -->
                                <div class="text-center mb-4">
                                    <div v-if="logoPreview" class="w-14 h-14 rounded-xl overflow-hidden mx-auto mb-2"
                                         :style="{ boxShadow: `0 0 0 2px ${previewPrimary}25` }">
                                        <img :src="logoPreview" class="w-full h-full object-cover" />
                                    </div>
                                    <div v-else class="w-14 h-14 rounded-xl mx-auto mb-2 flex items-center justify-center"
                                         :style="{ backgroundColor: previewPrimary + '15' }">
                                        <Icon name="restaurant" class="w-6 h-6" :style="{ color: previewPrimary }" />
                                    </div>
                                    <h2 class="font-bold text-sm" :style="{ color: previewText }">{{ form.name || 'Nome do Restaurante' }}</h2>
                                    <p class="text-[10px] mt-0.5 line-clamp-2" :style="{ color: previewTextSec }">{{ form.description || 'Sua descricao aqui...' }}</p>
                                </div>

                                <!-- Stories preview -->
                                <div class="flex gap-2 mb-3 overflow-hidden">
                                    <div v-for="i in 4" :key="i" class="flex-shrink-0 text-center">
                                        <div class="w-10 h-10 rounded-full p-0.5"
                                             :style="{ background: `linear-gradient(135deg, ${previewPrimary}, #ff8c00)` }">
                                            <div class="w-full h-full rounded-full" :style="{ backgroundColor: previewBg, border: `1.5px solid ${previewBg}` }">
                                                <div class="w-full h-full rounded-full" :style="{ backgroundColor: previewBorder }"></div>
                                            </div>
                                        </div>
                                        <p class="text-[7px] mt-0.5 w-10 truncate" :style="{ color: previewTextSec }">Story {{ i }}</p>
                                    </div>
                                </div>

                                <!-- Category pills -->
                                <div class="flex gap-1.5 mb-3">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-medium"
                                          :style="{ backgroundColor: previewPrimary, color: '#fff' }">Todos</span>
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-medium"
                                          :style="{ backgroundColor: previewBorder, color: previewTextSec }">Massas</span>
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-medium"
                                          :style="{ backgroundColor: previewBorder, color: previewTextSec }">Bebidas</span>
                                </div>

                                <!-- Menu items preview -->
                                <div class="space-y-2">
                                    <div v-for="i in 3" :key="i"
                                         class="flex gap-2 p-2 rounded-lg"
                                         :style="{ backgroundColor: previewBorder + '40', border: `1px solid ${previewBorder}50` }">
                                        <div class="w-12 h-12 rounded-md flex-shrink-0" :style="{ backgroundColor: previewBorder }">
                                            <div class="w-full h-full rounded-md flex items-center justify-center">
                                                <Icon name="image" class="w-4 h-4" :style="{ color: previewTextSec + '60' }" />
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0 py-0.5">
                                            <div class="h-2.5 rounded w-20 mb-1" :style="{ backgroundColor: previewText + '30' }"></div>
                                            <div class="h-2 rounded w-full mb-1.5" :style="{ backgroundColor: previewTextSec + '20' }"></div>
                                            <span class="text-[10px] font-bold" :style="{ color: previewPrice }">R$ 29,90</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="mt-4 pt-2 text-center" :style="{ borderTop: `1px solid ${previewBorder}40` }">
                                    <p class="text-[8px]" :style="{ color: previewTextSec + '60' }">
                                        Powered by <span class="font-bold"><span :style="{ color: previewPrimary }">Clica</span><span :style="{ color: previewText }">Food</span></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
