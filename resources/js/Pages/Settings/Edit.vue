<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    restaurant: Object,
});

const mediaUrl = (path) => path ? (path.startsWith('http') ? path : '/storage/' + path) : null;
const logoPreview = ref(props.restaurant?.logo_url || mediaUrl(props.restaurant?.logo));

const form = useForm({
    name: props.restaurant?.name || '',
    description: props.restaurant?.description || '',
    logo: null,
    primary_color: props.restaurant?.primary_color || '#E63B2E',
    secondary_color: props.restaurant?.secondary_color || '#1a1a1a',
    address: props.restaurant?.address || '',
    phone: props.restaurant?.phone || '',
    instagram: props.restaurant?.instagram || '',
    whatsapp: props.restaurant?.whatsapp || '',
    working_hours: props.restaurant?.working_hours || {},
});

const handleLogo = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post('/settings', {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout title="Configuracoes">
        <div class="max-w-2xl">
            <div class="mb-8">
                <h1 class="text-2xl font-display font-bold text-white">Configuracoes do Restaurante</h1>
                <p class="text-dark-400 mt-1">Personalize seu cardapio digital</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Logo e Nome -->
                <div class="card space-y-4">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <Icon name="restaurant" class="w-5 h-5 text-brand-400" />
                        Identidade
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
                    <p v-if="form.errors.logo" class="text-sm text-red-400">{{ form.errors.logo }}</p>

                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Nome do restaurante</label>
                        <input v-model="form.name" type="text" class="input-field" required />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-400">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Descricao / Bio</label>
                        <textarea v-model="form.description" class="input-field" rows="3"
                                  placeholder="Fale sobre seu restaurante..."></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-400">{{ form.errors.description }}</p>
                    </div>
                </div>

                <!-- Cores -->
                <div class="card space-y-4">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <Icon name="settings" class="w-5 h-5 text-brand-400" />
                        Cores do cardapio
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-dark-300 mb-1">Cor primaria</label>
                            <div class="flex items-center gap-2">
                                <input v-model="form.primary_color" type="color"
                                       class="w-10 h-10 rounded border border-dark-600 cursor-pointer" />
                                <input v-model="form.primary_color" type="text" class="input-field flex-1" />
                            </div>
                            <p v-if="form.errors.primary_color" class="mt-1 text-sm text-red-400">{{ form.errors.primary_color }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-dark-300 mb-1">Cor secundaria</label>
                            <div class="flex items-center gap-2">
                                <input v-model="form.secondary_color" type="color"
                                       class="w-10 h-10 rounded border border-dark-600 cursor-pointer" />
                                <input v-model="form.secondary_color" type="text" class="input-field flex-1" />
                            </div>
                            <p v-if="form.errors.secondary_color" class="mt-1 text-sm text-red-400">{{ form.errors.secondary_color }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contato -->
                <div class="card space-y-4">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <Icon name="phone" class="w-5 h-5 text-brand-400" />
                        Contato e Redes
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Endereco</label>
                        <input v-model="form.address" type="text" class="input-field"
                               placeholder="Rua, numero, bairro..." />
                        <p v-if="form.errors.address" class="mt-1 text-sm text-red-400">{{ form.errors.address }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-dark-300 mb-1">Telefone</label>
                            <input v-model="form.phone" type="text" class="input-field"
                                   placeholder="(11) 3333-4444" />
                            <p v-if="form.errors.phone" class="mt-1 text-sm text-red-400">{{ form.errors.phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-dark-300 mb-1">WhatsApp</label>
                            <input v-model="form.whatsapp" type="text" class="input-field"
                                   placeholder="5511999999999" />
                            <p v-if="form.errors.whatsapp" class="mt-1 text-sm text-red-400">{{ form.errors.whatsapp }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Instagram</label>
                        <div class="flex items-center gap-2">
                            <Icon name="instagram" class="w-5 h-5 text-dark-400" />
                            <input v-model="form.instagram" type="text" class="input-field flex-1"
                                   placeholder="@seurestaurante" />
                        </div>
                        <p v-if="form.errors.instagram" class="mt-1 text-sm text-red-400">{{ form.errors.instagram }}</p>
                    </div>
                </div>

                <button type="submit" :disabled="form.processing" class="btn-primary">
                    {{ form.processing ? 'Salvando...' : 'Salvar configuracoes' }}
                </button>
            </form>
        </div>
    </AppLayout>
</template>
